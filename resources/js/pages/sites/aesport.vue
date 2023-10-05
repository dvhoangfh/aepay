<template>
  <div class="content-page pricing-page">
    <div class="container" style="padding-top: 0">
      <div class="btn-checkout-mobile btn btn-blue d-none" @click="checkoutPaddle">Check out</div>
      <div class="detail-plan" style="margin-top: 0; margin-bottom: 0">
        <div class="left">
          <div class="title">
            Your Plan
          </div>
          <div class="titleUtilities">
            Utilities
          </div>
          <div class="package">
            <p>{{ package.name || '' }} <br>
              <span v-if="parseInt(package.billing_period) > 0">for {{
                  package.billing_period
                }} {{ package.billing_type }}<span v-if="package.billing_period > 1">s</span></span>
            </p>
            <p>{{ package.amount * package.billing_period | toUSD }} <span>$</span></p>
          </div>
          <!--                    <div class="checkout" @click="checkoutPaddle">Checkout</div>-->
          <div>

          </div>
          <div v-if="paypal_unavailable && !is_enable_cc" class="paypal-unavailable">
            <img src="../../../images/paypal.svg" alt="">
            <img src="../../../images/error.svg" alt="">
            <div class="title-error">
              We’re sorry about that
            </div>
            <div class="description">
              The paypal not available on your country OR your network error.
              Please contact with our chat support !
            </div>
          </div>
          <template v-else>
            <div class="d-flex gap-1 mt-2">
              <input type="text" class="form-control" placeholder="Enter promo code if you have one"
                     v-model="voucher">
              <button style="border-radius: 10px" type="button" class="btn btn-primary" @click="onApply">
                Validate
              </button>
            </div>
            <p v-if="voucher_success" class="text-success mt-4">{{ voucher_success }}</p>
            <p v-if="voucher_error" class="text-danger mt-4">{{ voucher_error }}</p>
            <div v-if="is_enable_cc" class="cc-button-container" @click="onCheckoutCC">
              <img src="../../../images/jcb@2x.png" alt="">
              <img src="../../../images/mastercard@2x.png" alt="">
              <img src="../../../images/visa@2x.png" alt="">
            </div>
            <div style="margin-top: 20px">
              <div id="paypal-button-container"></div>
            </div>
            <div class="secure-container">
              <div class="text">
                Secure by
              </div>
              <div class="certificate">
                <img src="../../../images/certificate-logo-1.png" alt="">
                <img src="../../../images/certificate-logo-2.png" alt="">
                <img src="../../../images/certificate-logo-3.png" alt="">
                <img src="../../../images/seal_image.png" alt="">
              </div>
            </div>
          </template>
          <div class="description">
            <ul>
              <!--                            <li>1. No ads/popups</li>-->
              <!--                            <li>2. Up to FulHD, 4k quality</li>-->
              <!--                            <li>4. Multiple language audio</li>-->
              <!--                            <li>5. 20+ live sports channels</li>-->
              <!--                            <li>6. Global CDN for fastest live streaming</li>-->
            </ul>
          </div>
        </div>
        <div class="line-up"></div>
        <div class="right">
          <div class="title">
            Benefits
          </div>
          <div class="description">
            <ul>
              <li>1. Cancel your account anytime</li>
              <li>2. We do not store card number. The security of your personal data is your priority</li>
              <li>3. The membership renew auto automatically
              </li>
              <li>4. We are using secure protocol with SSL technology (Secure Socket layer) for personal
                in information and billing information to send data on world wide web.
              </li>
              <li>5. No ads/popups</li>
              <li>6. Up to FulHD, 4k quality</li>
              <li>7. Multiple language audio</li>
              <li>8. 20+ live sports channels</li>
              <li>9. Global CDN for fastest live streaming</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="detail-plan" style="margin-top: unset; margin-bottom: unset">
        <div>By purchasing, you accept the <a target="_blank" href="https://aesport.tv/term">Terms of
          Service</a> and
          acknowledge
          reading the <a target="_blank" href="https://aesport.tv/faq">FAQ</a>.
          You
          also
          agree to our <a target="_blank" href="https://aesport.tv/refund-policy">Refund Policy</a> and auto
          renewal of your
          subscription,
          which can be disabled at
          any
          time
          through your account. Your card details will be saved for future purchases and subscription
          renewals.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import payment from "../../mixins/payment";

export default {
  name: "aesport",
  mixins: [payment]
}
</script>

<style scoped lang="scss">
.paypal-unavailable {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;

  .title-error {
    margin-top: 8px;
    font-size: 26px;
    color: #ffffff;
  }

  .description {
    font-weight: 300;
    font-size: 16px;
    line-height: 24px;
    margin-top: 16px;
  }

  .btn-try-again {
    margin-top: 40px;
    padding: 10px 50px;
    font-weight: 600;
    font-size: 16px;
    line-height: 24px;
    color: #000000;
    background: #00EFFF;
    border-radius: 6px;
  }
}

.cc-button-container {
  background: #d8d8d8;
  border-radius: 10px;
  height: 55px;
  margin-top: 20px;
  display: flex;
  justify-content: center;
  cursor: pointer;

  img {
    width: 90px;
    object-fit: cover;
  }

  @media only screen and (max-width: 768px) {
    height: 35px;
    img {
      width: 55px;
    }
  }
}

.secure-container {
  display: flex;
  gap: 5px;
  align-items: center;

  .text {
    color: white;
  }

  .certificate {
    display: flex;
    gap: 10px;

    img {
      width: 65px;
      object-fit: contain;
    }
  }

  @media only screen and (max-width: 768px) {
    flex-direction: column;
  }
}
</style>
