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
          <div v-if="paypal_unavailable && is_enable_paypal" class="paypal-unavailable">
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
            <div v-if="is_enable_sellix" class="sellix-button-container" @click="onCheckoutSellix">
              <svg width="100" height="40" viewBox="0 0 43 13" fill="" xmlns="http://www.w3.org/2000/svg"
                   class="ml-1" style="min-width: 43px;">
                <path
                    d="M3.17695 3.72095L6.48339 2.61713L6.26087 3.55752C6.54751 3.62541 6.8593 3.71593 7.20377 3.83913C7.96815 4.11195 8.66087 4.42876 9.28319 4.78832L10.6586 1.89047C10.0036 1.47559 9.23164 1.14243 8.34154 0.892249C7.45144 0.640808 6.56008 0.515088 5.66496 0.515088C4.68308 0.515088 3.82064 0.668467 3.07763 0.973967C2.33463 1.27947 1.7626 1.71949 1.35904 2.29152C0.955474 2.86606 0.753065 3.53489 0.753065 4.29801C0.753065 5.08376 0.949188 5.71111 1.34269 6.1813C1.73494 6.65024 2.20136 6.99723 2.74196 7.22101C3.28256 7.44479 3.95642 7.65977 4.76354 7.86721C4.98104 7.92127 5.17339 7.97281 5.35317 8.02562L5.56689 7.12169L8.13536 9.14453L4.83017 10.2484L5.00744 9.49654C4.5825 9.41105 4.11357 9.27025 3.58554 9.05527C2.71304 8.69948 1.98638 8.27077 1.40807 7.76915L0 10.6343C0.753065 11.2126 1.64568 11.6677 2.67784 12.0009C3.70875 12.3341 4.73211 12.5 5.74667 12.5C6.6418 12.5 7.45521 12.363 8.1869 12.0902C8.9186 11.8173 9.49942 11.3999 9.93064 10.838C10.3619 10.276 10.5781 9.58078 10.5781 8.75102C10.5781 7.94264 10.3757 7.29141 9.97213 6.79482C9.56857 6.29822 9.09586 5.93489 8.55527 5.70608C8.01467 5.47727 7.33578 5.2472 6.51734 5.01839C6.3099 4.96307 6.12761 4.91027 5.95537 4.85872L5.74542 5.74379L3.17695 3.72095Z"
                    fill="var(--black4)"></path>
                <path
                    d="M15.324 4.18359C12.7707 4.18359 11.0181 6.03293 11.0181 8.32607C11.0181 10.9297 12.9492 12.4849 15.4221 12.4849C16.6001 12.4849 18.0094 12.1744 18.7952 11.5018L17.5832 9.68511C17.191 10.0283 16.2078 10.242 15.7653 10.242C14.7998 10.242 14.2265 9.75048 14.0631 9.24383H19.4326V8.63786C19.4338 5.85315 17.6486 4.18359 15.324 4.18359ZM14.0304 7.37563C14.1284 7.01607 14.4226 6.42644 15.3228 6.42644C16.2732 6.42644 16.5511 7.03241 16.6328 7.37563H14.0304Z"
                    fill="var(--black4)"></path>
                <path d="M23.3953 1.36746H20.4484V12.2875H23.3953V1.36746Z" fill="var(--black4)"></path>
                <path d="M27.979 1.36746H25.0322V12.2875H27.979V1.36746Z" fill="var(--black4)"></path>
                <path d="M32.5641 4.37973H29.6172V12.2875H32.5641V4.37973Z" fill="var(--black4)"></path>
                <path
                    d="M31.0906 0.5C30.1741 0.5 29.4361 1.23672 29.4361 2.15322C29.4361 3.06972 30.1729 3.8077 31.0906 3.8077C32.0071 3.8077 32.7438 3.07098 32.7438 2.15322C32.7438 1.23672 32.0071 0.5 31.0906 0.5Z"
                    fill="var(--black4)"></path>
                <path
                    d="M39.5051 8.22677L41.9277 4.37973H38.703L37.6381 6.23033L36.5745 4.37973H33.3486L35.7724 8.22677L33.1851 12.2875H36.3935L37.6545 10.1918L38.8828 12.2875H42.1075L39.5051 8.22677Z"
                    fill="var(--black4)"></path>
              </svg>
            </div>
            <div v-if="is_enable_paypal" style="margin-top: 20px" class="wp-button-container" @click="onCheckoutWp">
              24Gift
            </div>
            <div v-if="is_enable_bytepay" class="cc-button-container" @click="onCheckoutCC">
              <img src="vendor/aepay/images/jcb@2x.png" alt="">
              <img src="vendor/aepay/images/mastercard@2x.png" alt="">
              <img src="vendor/aepay/images/visa@2x.png" alt="">
            </div>
            <div v-if="is_enable_paypal" style="margin-top: 20px">
              <div id="paypal-button-container"></div>
            </div>
            <div class="secure-container">
              <div class="text">
                Secure by
              </div>
              <div class="certificate">
                <img src="/vendor/aepay/images/certificate-logo-1.png" alt="">
                <img src="vendor/aepay/images/certificate-logo-2.png" alt="">
                <img src="vendor/aepay/images/certificate-logo-3.png" alt="">
                <img src="vendor/aepay/images/seal_image.png" alt="">
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

.sellix-button-container, .wp-button-container {
  background: #e5edee;
  border-radius: 10px;
  height: 55px;
  margin-top: 20px;
  display: flex;
  justify-content: center;
  cursor: pointer;
  align-items: center;
  img {
    width: 90px;
    object-fit: cover;
  }
  @media only screen and (max-width: 768px) {
    height: 35px;
    svg {
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
