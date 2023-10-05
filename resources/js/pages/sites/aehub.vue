<template>
    <div>
        <div class="check-out__container">
            <div class="check-out__column your-plan">
                <div class="text-heading text-uppercase">Your Plan</div>
                <div class="your-plan__detail">
                    <div class="plan">
                        <div class="detail">
                            <div>
                                <span class="title">{{ package.name || '' }}</span>
                                <span class="days">({{ package.day_value }} day<span v-if="package.day_value > 0">s</span>)</span>
                            </div>
                            <div>
                                <span class="per-day">{{ package.per_day }}€ per {{ getPer(package)}}</span>
                                <span class="save" v-if="package.save_value">Save {{ package.save_value }}%</span>
                            </div>
                        </div>
                        <div class="price">
                            {{ package.amount * package.billing_period | toUSD }} <span>€</span>
                        </div>
                    </div>
                    <div class="checkout">
                        <div class="checkout__item subscribe">
                            <div class="text-uppercase heading">Subscribe</div>
                            <div id="paypal-button-container"></div>
                        </div>
                        <div class="checkout__item discount">
                            <div class="text-uppercase heading">Promo code</div>
                            <input type="text" class="form-control input-code" placeholder="Enter promo code if you have one" v-model="voucher">
                            <button class="btn btn-primary btn-validate" @click="onApply">Validate</button>
                            <p v-if="voucher_success" class="text-success mt-4">{{ voucher_success }}</p>
                            <p v-if="voucher_error" class="text-danger mt-4">{{ voucher_error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="check-out__column benefits">
                <div class="text-heading text-uppercase text-center">Benefits</div>
                <div v-for="benefit in benefits" class="benefits__item" :key="benefit">
                    <checked-icon/>
                    <div>{{ benefit }}</div>
                </div>

            </div>
        </div>
        <div class="mt-5 policy">
            By purchasing, you accept the <a target="_blank" href="https://ggsport.tv/term">Term</a> of Service and acknowledge reading the
            <a target="_blank" href="https://ggsport.tv/faq">FAQ</a>. You also agree to our <a target="_blank" href="https://ggsport.tv/copyright">Refund Policy</a> and auto renewal of your subscription, which can be disabled at any time through your account. Your card details will be saved for future purchases and subscription renewals.
        </div>
    </div>

</template>

<script>
import payment from "../../mixins/payment";
import CheckedIcon from "../../components/checkedIcon";

export default {
    name: "aesport",
    components: {CheckedIcon},
    mixins: [payment],
    setup() {
        const benefits = [
            'HD live streaming',
            'Watch on your laptop, phone or tablet',
            'Multiple language commentary',
            'Unlimited watching time',
            '100+ football leagues and tournaments',
            'Full-screen mode',
            'No ads/popups',
            '24/7 dedicated support',
            'Watch on 2 devices at the same time',
        ];

        const getPer = (pack) => {
            return ['3m', '1y'].includes(pack.package_hash_id) ? 'month': 'day';
        }

        return {
            benefits,
            getPer
        }
    }
}
</script>

<style scoped lang="scss">
$text-secondary-3: #646B7E;
$text-secondary-5: #FAFAFA;
$main-background: rgb(30 31 33);
$column-bg: rgb(37 37 39);
.check-out {
    &__container {
        background: $main-background;
        color: $text-secondary-5;
        display: flex;
        gap: 30px;
    }
    &__column {
        background: $column-bg;
        border-radius: 16px;
        padding: 16px 24px;
        .text-heading {
            color: $text-secondary-3;
            font-size: 20px;
            line-height: 24px;
            margin-bottom: 16px;
            font-weight: 700;
            text-transform: uppercase;
        }
        &.your-plan {
            flex: 1;
        }
        .benefits {
            &__item {
                display: flex;
                gap: 10px;
                align-items: center;
                margin-top: 10px;
            }
        }
        .your-plan {
            &__detail {
                .plan {
                    border: 1px solid #393C40;
                    border-radius: 8px;
                    padding: 12px 40px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 16px;
                    .title {
                        font-weight: 700;
                        font-size: 20px;
                        line-height: 24px;
                        margin-right: 4px;
                    }
                    .days {
                        font-weight: 500;
                        font-size: 14px;
                        line-height: 18px;
                        color: #646B7E;
                    }
                    .per-day {
                        font-weight: 500;
                        font-size: 14px;
                        line-height: 18px;
                        color: #DAFBFF;
                        margin-right: 4px;
                    }
                    .save {
                        font-weight: 700;
                        font-size: 14px;
                        line-height: 18px;
                        color: #F59300;
                    }
                    .price {
                        color: #59FFFF;
                        font-size: 24px;
                        line-height: 32px;
                    }
                }
                .checkout {
                    display: flex;
                    gap: 16px;
                    width: 100%;
                    &__item {
                        background: #1E1F21;
                        border-radius: 16px;
                        flex: 1;
                        padding: 24px 12px;
                        position: relative;
                        .heading {
                            font-style: normal;
                            font-weight: 800;
                            font-size: 16px;
                            line-height: 20px;
                            color: #DAFBFF;
                            position: absolute;
                            top: -8px;
                            left: 50%;
                            transform: translateX(-50%);
                            background: #252527;
                            border-radius: 16px;
                            padding: 4px 12px;
                        }
                    }
                    .subscribe {
                    }
                    .discount {
                        display: flex;
                        flex-direction: column;
                        gap: 12px;
                        .input-code {
                            background: #393C40;
                            color: $text-secondary-5;
                            border-radius: 8px;
                            border: none;
                            height: 42px;
                        }
                        .btn-validate {
                            background: #59FFFF;
                            border-radius: 8px;
                            color: #1E1F21;
                            font-weight: 800;
                            font-size: 16px;
                            line-height: 20px;
                            width: 100%;
                        }
                    }
                }
            }
        }
    }
}
.policy {
    color: #ffffff;
    padding: 0 24px;
}
@media (max-width: 576px) {
    .check-out {
        &__container {
            flex-direction: column;
        }
        &__column {
            .your-plan {
                &__detail {
                    .plan {
                        padding: 12px 12px;
                    }
                    .checkout {
                        flex-direction: column;
                    }
                }
            }
        }
    }
}
</style>
