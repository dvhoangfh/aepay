<template>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-8 section-heading">
                    <h2 class="text-uppercase wow fadeTop" data-wow-delay="0.1s"
                        style="visibility: visible; animation-delay: 0.1s; animation-name: fadeTop;">Our Pricing</h2>
                    <h4 class="text-uppercase source-font wow fadeTop" data-wow-delay="0.2s"
                        style="visibility: visible; animation-delay: 0.2s; animation-name: fadeTop;">- Choose Your Plan
                        -</h4>
                </div>
            </div>
            <div class="row mt-50">
                <div class="col-md-4 col-sm-6 fadeTop wow"
                     :class="{ 'pricing-table-featured': pack.is_recommend, 'pricing-table': !pack.is_recommend }"
                     data-wow-delay="0.1s" style="visibility: visible; animation-delay: 0.1s; animation-name: fadeTop;"
                     v-for="pack in packages" :key="pack.id">
                    <div class="pricing-box">
                        <h4>{{ pack.name }}</h4>
                        <h2><sup>$</sup><span>{{ pack.amount | toUSD }}</span></h2>
                        <ul>
                            <li>No attribution required</li>
                            <li>Unlimited downloads</li>
                            <li>Priority support</li>
                        </ul>
                        <div class="pricing-box-bottom"><a href="javascript:void(0)"
                                                           class="btn btn-color btn-circle btn-animate"
                                                           @click="choosePlan(pack)"><span>Purchase Now <i
                            class="mdi mdi-arrow-right"></i></span></a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section style="padding-top: 0px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="payment-box">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="summary-cart">
                                            <h6 class="upper-case">Order Details</h6>
                                            <table class="order_table table-responsive table">
                                                <tbody>
                                                <tr>
                                                    <th>Item</th>
                                                    <td><span>{{ package.name }}</span></td>
                                                </tr>
                                                <tr>
                                                    <th>Total</th>
                                                    <td><h6><strong>${{ package.amount | toUSD }}</strong></h6></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="check-btns">
                                                <button class="btn btn-color btn-animate" @click="checkoutPaddle"><span>Proceed to Checkout <i
                                                    class="mdi mdi-check"></i></span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import api from "../api";

export default {
    name: "Package",
    filters: {
        toUSD(value) {
            return `$ ${value.toLocaleString()}`
        }
    },
    data() {
        return {
            packages: JSON.parse(Packages),
            app_name: AppName,
            user: JSON.parse(User),
            package: {},
            payment_methods: [
                {
                    name: 'paddle'
                },
                {
                    name: 'paypal'
                }
            ]
        }
    },
    mounted() {
        this.package = this.packages.find((pack) => {
            return pack.is_recommend === 1
        })
    },
    methods: {
        choosePlan(plan) {
            this.package = plan
        },
        async checkoutPaddle() {
            if (this.user.length === 0) {
                window.location.href = '/auth/login'
            }
            Paddle.Spinner.show()
            try {
                let payload = {
                    package_id: this.package.paddle_id
                }
                let response = await api.getPayLink(payload)
                if (response.data.url) {
                    Paddle.Checkout.open({
                        override: response.data.url
                    })
                } else {
                    Paddle.Spinner.hide()
                }
            } catch (e) {
                Paddle.Spinner.hide()
                console.log('e', e)
                alert('Something went wrong')
            }
        },
        checkoutComplete() {

        }
    }
}
</script>

<style scoped>

</style>
