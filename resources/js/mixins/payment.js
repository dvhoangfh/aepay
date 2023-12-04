import api from "../api";

export default {
    filters: {
        toUSD(value) {
            return `${value.toLocaleString()}`
        }
    },
    data() {
        return {
            packages: [],
            user_id: 0,
            package: {},
            user: {},
            plans: {},
            payment_methods: [
                {
                    name: 'paddle'
                },
                {
                    name: 'paypal'
                }
            ],
            buttons: '',
            payment_id: 0,
            voucher: '',
            voucher_valid: '',
            vouchers: [],
            voucher_success: '',
            voucher_error: '',
            paypal_unavailable: false,
            url_redirect: '',
            url_callback: '',
            is_enable_paypal: false,
            is_enable_bytepay: false,
            is_enable_sellix: false,
            is_enable_wordpress: false,
        }
    },
    computed: {
        vouchersHasPlan() {
            return this.vouchers.filter((v) => v.plans && v.plans.length)
        }
    },
    async mounted() {
        await this.$nextTick()
        this.packages = JSON.parse(Packages)
        this.user_id = JSON.parse(UserId)
        this.user = JSON.parse(User)
        this.plans = JSON.parse(Plans)
        this.package = this.packages.find((pack) => {
            return pack.is_recommend === 1
        })
        this.payment_id = JSON.parse(PaymentId)
        this.vouchers = JSON.parse(Vouchers)
        this.url_redirect = JSON.parse(UrlRedirect)
        this.url_callback = JSON.parse(UrlCallback)
        this.is_enable_paypal = JSON.parse(IsEnablePaypal)
        this.is_enable_bytepay = JSON.parse(IsEnableBytePay)
        this.is_enable_sellix = JSON.parse(IsEnableSellix)
        this.is_enable_wordpress = JSON.parse(IsWordPress)
        window.addEventListener("message", this.choosePlan, false)
        try {
            await this.mountPaypalButton()
        } catch (e) {
            this.paypal_unavailable = true
            console.log(e)
        }

    },
    methods: {
        async checkoutPaddle() {
            Paddle.Spinner.show()
            try {
                let payload = {
                    package_id: this.package.paddle_id,
                    user_id: this.user_id
                }
                let response = await api.getPayLinkUid(payload)
                if (response.data.url) {
                    Paddle.Checkout.open({
                        override: response.data.url
                    })
                } else {
                    Paddle.Spinner.hide()
                }
            } catch (e) {
                Paddle.Spinner.hide()
                alert('Something went wrong')
            }
        },
        async choosePlan(event) {
            let eventType = event.data;
            if (typeof eventType !== "undefined" && typeof eventType === "string") {
                const dataPackage = JSON.parse(eventType).value
                if (!dataPackage) return
                this.package = this.packages.find((p) => p.package_hash_id === dataPackage.package_hash_id)
                this.mountPaypalButton()
                this.voucher = ''
            }
        },
        onSendEventToParent(data) {
            sendEventToParent('payment-success', data)
        },
        mountPaypalButton() {
            const paypalPlanId = this.plans[this.package.package_hash_id]
            const packageId = this.package.id
            const userId = this.user_id
            const userEmail = this.user.email
            const paymentId = this.payment_id
            if (this.buttons && this.buttons.close) {
                this.buttons.close();
            }
            this.buttons = paypal.Buttons({
                style: {
                    shape: 'rect',
                    color: 'gold',
                    layout: 'vertical',
                    label: 'subscribe'
                },
                createSubscription: async (data, actions) => {
                    return actions.subscription.create({
                        plan_id: paypalPlanId,
                        subscriber: {
                            name: {
                                given_name: paymentId,
                                surname: userEmail,
                            },
                            email_address: userEmail,
                            voucher: this.voucher_valid
                        },
                        application_context: {
                            shipping_preference: "NO_SHIPPING",
                        }
                    });
                },
                onApprove: async (data) => {
                    try {
                        data.userId = userId
                        data.voucher = this.voucher_valid
                        data.package_id = packageId
                        data.payment_id = paymentId
                        data.userEmail = userEmail
                        data.paypalPlanId = paypalPlanId
                        data.site = JSON.parse(Site)
                        console.log('sendEventToParent');
                        sendEventToParent('payment-success', {
                            url: "/thank-you?user_id=" + userId + "&package_id=" + packageId + "&type=paypal&&receive_code=" + data.orderID + "&subscriptionID=" + data.subscriptionID,
                            fullData: data
                        })
                        await this.charge(data)
                    } catch (e) {
                        await this.handleLog(e)
                    }

                },
                onError: () => {
                    return (err) => {
                        this.paypal_unavailable = true
                    }
                },
            })
            this.buttons.render('#paypal-button-container');
            // Renders the PayPal button
        },
        onApply() {
            const voucher = this.voucher
            const validVoucher = this.isVoucherValid(voucher)
            if (validVoucher) {
                this.voucher_valid = voucher
                this.voucher_success = `Success bonus ${validVoucher.extra_day} ${validVoucher.extra_day > 1 ? 'days' : 'day'}`
                this.voucher_error = ''
            } else {
                this.voucher_error = 'Voucher invalid'
                this.voucher_success = ''
            }
        },
        isVoucherValid(voucher) {
            return this.vouchersHasPlan.find((v) => v.code === voucher && this.getValidPlans(v.plans).includes(this.package.package_hash_id))
        },
        getValidPlans(plans) {
            return plans.split(",") || [];
        },
        async charge(data) {
            await api.charge(data)
        },
        async handleLog(data) {
            await api.handleLog(data)
        },
        async onCheckoutCC() {
            const payload = {
                'package_id': this.package.id,
                'user_id': this.user.id,
                'url_redirect': this.url_redirect,
                'site': JSON.parse(Site)
            }
            let response = await api.getPayLinkBytePay(payload)
            if (response.data.url) {
                sendEventToParent('url', { url: response.data.url})
            }
        },
        async onCheckoutSellix() {
            const payload = {
                'package_id': this.package.id,
                'user_id': this.user.id,
                'url_redirect': this.url_redirect
            }
            let response = await api.createSellixSubscription(payload)
            if (response.data.url) {
                sendEventToParent('url', {url: response.data.url})
            }
        },
        async onCheckoutWp() {
            const payload = {
                'package_id': this.package.id,
                'user_id': this.user.id,
                'site': JSON.parse(Site)
            }
            let response = await api.createWordpressOrder(payload)
            if (response.data.url) {
                sendEventToParent('url', { url: response.data.url})
            }
        }
    }
}
