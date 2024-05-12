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
          <div v-if="paypal_unavailable && settings.is_enable_paypal" class="paypal-unavailable">
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
            <div v-if="settings.is_enable_sellix" class="sellix-button-container" @click="onCheckoutSellix">
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
            <div v-if="settings.is_enable_wordpress_paypal" style="margin-top: 20px" class="wp-button-container"
                 @click="onCheckoutWp('paypal')">
              <loading v-if="loading.paypal"/>
              <div v-else>
                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="30" viewBox="0 0 338.667 89.785"
                     xmlns:v="https://vecta.io/nano">
                  <g transform="translate(936.898 -21.779)">
                    <path clip-path="none"
                          d="M-828.604 39.734c-.697 0-1.289.506-1.398 1.195l-8.068 51.165a1.31 1.31 0 0 0 1.294 1.513h9.568c.696 0 1.289-.507 1.398-1.195l2.37-15.025c.108-.688.701-1.195 1.398-1.195h8.699c10.164 0 18.792-7.416 20.368-17.465 1.589-10.134-6.328-18.971-17.549-18.993zm9.301 11.422h6.96c5.73 0 7.596 3.381 7.006 7.12-.59 3.747-3.488 6.507-9.031 6.507h-7.084zm45.788 3.478c-2.416.009-5.196.504-8.317 1.804-7.159 2.984-10.597 9.151-12.057 13.647 0 0-4.647 13.717 5.852 21.253 0 0 9.737 7.255 20.698-.447l-.189 1.203a1.31 1.31 0 0 0 1.292 1.513h9.083c.697 0 1.289-.507 1.398-1.195l5.525-35.038a1.31 1.31 0 0 0-1.292-1.515h-9.083c-.697 0-1.29.507-1.398 1.195l-.297 1.886s-3.967-4.333-11.216-4.306zm.297 11.067c1.043 0 1.997.144 2.853.419 3.919 1.258 6.141 5.023 5.498 9.104-.793 5.025-4.914 8.725-10.199 8.725-1.042 0-1.996-.143-2.853-.418-3.918-1.258-6.154-5.023-5.511-9.104.793-5.025 4.927-8.727 10.212-8.727z"
                          fill="#003087"/>
                    <path clip-path="none"
                          d="M-697.804 39.734c-.697 0-1.289.506-1.398 1.195l-8.068 51.165a1.31 1.31 0 0 0 1.294 1.513h9.568c.696 0 1.289-.507 1.398-1.195l2.37-15.025c.108-.688.701-1.195 1.398-1.195h8.699c10.164 0 18.791-7.416 20.366-17.465 1.59-10.134-6.326-18.971-17.547-18.993zm9.301 11.422h6.96c5.73 0 7.596 3.381 7.006 7.12-.59 3.747-3.487 6.507-9.031 6.507h-7.084zm45.787 3.478c-2.416.009-5.196.504-8.317 1.804-7.159 2.984-10.597 9.151-12.057 13.647 0 0-4.645 13.717 5.854 21.253 0 0 9.735 7.255 20.697-.447l-.189 1.203a1.31 1.31 0 0 0 1.294 1.513h9.082c.697 0 1.289-.507 1.398-1.195l5.527-35.038a1.31 1.31 0 0 0-1.294-1.515h-9.083c-.697 0-1.29.507-1.398 1.195l-.297 1.886s-3.967-4.333-11.216-4.306zm.297 11.067c1.043 0 1.997.144 2.853.419 3.919 1.258 6.141 5.023 5.498 9.104-.793 5.025-4.914 8.725-10.199 8.725-1.042 0-1.996-.143-2.853-.418-3.918-1.258-6.154-5.023-5.511-9.104.793-5.025 4.927-8.727 10.212-8.727z"
                          fill="#0070e0"/>
                    <path clip-path="none"
                          d="M-745.92 55.859c-.72 0-1.232.703-1.012 1.388l9.958 30.901-9.004 14.562c-.437.707.071 1.62.902 1.62h10.642a1.77 1.77 0 0 0 1.513-.854l27.811-46.007c.427-.707-.083-1.611-.909-1.611h-10.641a1.77 1.77 0 0 0-1.522.869l-10.947 18.482-5.557-18.345c-.181-.597-.732-1.006-1.355-1.006z"
                          fill="#003087"/>
                    <path clip-path="none"
                          d="M-609.107 39.734c-.696 0-1.289.507-1.398 1.195l-8.07 51.163a1.31 1.31 0 0 0 1.294 1.515h9.568c.696 0 1.289-.507 1.398-1.195l8.068-51.165a1.31 1.31 0 0 0-1.292-1.513z"
                          fill="#0070e0"/>
                    <path clip-path="none"
                          d="M-908.37 39.734a2.59 2.59 0 0 0-2.556 2.185l-4.247 26.936c.198-1.258 1.282-2.185 2.556-2.185h12.445c12.525 0 23.153-9.137 25.095-21.519a20.76 20.76 0 0 0 .245-2.793c-3.183-1.669-6.922-2.624-11.019-2.624z"
                          fill="#001c64"/>
                    <path clip-path="none"
                          d="M-874.832 42.359a20.76 20.76 0 0 1-.245 2.793c-1.942 12.382-12.571 21.519-25.095 21.519h-12.445c-1.273 0-2.358.926-2.556 2.185l-3.905 24.752-2.446 15.528a2.1 2.1 0 0 0 2.075 2.43h13.508a2.59 2.59 0 0 0 2.556-2.185l3.558-22.567a2.59 2.59 0 0 1 2.558-2.185h7.953c12.525 0 23.153-9.137 25.095-21.519 1.379-8.788-3.047-16.784-10.611-20.75z"
                          fill="#0070e0"/>
                    <path clip-path="none"
                          d="M-923.716 21.779c-1.273 0-2.358.926-2.556 2.183l-10.6 67.216c-.201 1.276.785 2.43 2.077 2.43h15.719l3.903-24.752 4.247-26.936a2.59 2.59 0 0 1 2.556-2.185h22.519c4.098 0 7.836.956 11.019 2.624.218-11.273-9.084-20.58-21.873-20.58z"
                          fill="#003087"/>
                  </g>
                </svg>
                <span class="text">One-Time</span>
              </div>
            </div>
            <div v-if="settings.is_enable_wordpress_stripe" class="cc-button-container"
                 @click="onCheckoutWp('stripe')">
              <loading v-if="loading.stripe"/>
              <div v-else>
                <span class="text">Visa or Credit Card By</span>
                <svg viewBox="0 0 60 25" xmlns="http://www.w3.org/2000/svg" width="60" height="25"
                     class="UserLogo variant-- "><title>Stripe logo</title>
                  <path fill="var(--userLogoColor, #0A2540)"
                        d="M59.64 14.28h-8.06c.19 1.93 1.6 2.55 3.2 2.55 1.64 0 2.96-.37 4.05-.95v3.32a8.33 8.33 0 0 1-4.56 1.1c-4.01 0-6.83-2.5-6.83-7.48 0-4.19 2.39-7.52 6.3-7.52 3.92 0 5.96 3.28 5.96 7.5 0 .4-.04 1.26-.06 1.48zm-5.92-5.62c-1.03 0-2.17.73-2.17 2.58h4.25c0-1.85-1.07-2.58-2.08-2.58zM40.95 20.3c-1.44 0-2.32-.6-2.9-1.04l-.02 4.63-4.12.87V5.57h3.76l.08 1.02a4.7 4.7 0 0 1 3.23-1.29c2.9 0 5.62 2.6 5.62 7.4 0 5.23-2.7 7.6-5.65 7.6zM40 8.95c-.95 0-1.54.34-1.97.81l.02 6.12c.4.44.98.78 1.95.78 1.52 0 2.54-1.65 2.54-3.87 0-2.15-1.04-3.84-2.54-3.84zM28.24 5.57h4.13v14.44h-4.13V5.57zm0-4.7L32.37 0v3.36l-4.13.88V.88zm-4.32 9.35v9.79H19.8V5.57h3.7l.12 1.22c1-1.77 3.07-1.41 3.62-1.22v3.79c-.52-.17-2.29-.43-3.32.86zm-8.55 4.72c0 2.43 2.6 1.68 3.12 1.46v3.36c-.55.3-1.54.54-2.89.54a4.15 4.15 0 0 1-4.27-4.24l.01-13.17 4.02-.86v3.54h3.14V9.1h-3.13v5.85zm-4.91.7c0 2.97-2.31 4.66-5.73 4.66a11.2 11.2 0 0 1-4.46-.93v-3.93c1.38.75 3.1 1.31 4.46 1.31.92 0 1.53-.24 1.53-1C6.26 13.77 0 14.51 0 9.95 0 7.04 2.28 5.3 5.62 5.3c1.36 0 2.72.2 4.09.75v3.88a9.23 9.23 0 0 0-4.1-1.06c-.86 0-1.44.25-1.44.9 0 1.85 6.29.97 6.29 5.88z"
                        fill-rule="evenodd"></path>
                </svg>
              </div>
            </div>
            <div v-if="settings.is_enable_wordpress_paycec" style="margin-top: 20px" class="wp-button-container"
                 @click="onCheckoutWp('paycec')">
              <loading v-if="loading.paycec"/>
              <div v-else>
                <span class="text">Visa or Credit Card By</span>
                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     width="111px" height="33px" viewBox="0.7 -2.712 111 33" enable-background="new 0.7 -2.712 111 33"
                     xml:space="preserve">
<g>
	<g>
		<polygon fill="#B31F24" points="39.154,29.059 42.58,21.72 36.458,6.813 41.412,6.813 45.205,15.978 49.391,6.813 54.375,6.813
			44.173,29.059 		"/>
    <path fill="#B31F24" d="M30.924,23.608v-0.939c-1.121,0.544-2.357,0.831-3.615,0.831c-4.57,0-8.287-3.718-8.287-8.287
			c0-4.572,3.717-8.29,8.287-8.29c1.258,0,2.494,0.286,3.615,0.83v-0.94h4.672v16.796H30.924z M27.309,11.285
			c-2.166,0-3.927,1.761-3.927,3.927c0,2.161,1.761,3.922,3.927,3.922c2.164,0,3.926-1.761,3.926-3.922
			C31.234,13.046,29.474,11.285,27.309,11.285z"/>
    <path fill="#B31F24" d="M19.079,2.601c-1.551-2.551-4.257-4.075-7.238-4.075H1.178v25.083h6.573v-8.157h4.09
			c3.864,0,7.235-2.611,8.196-6.349c0.178-0.687,0.267-1.398,0.267-2.116C20.305,5.437,19.88,3.92,19.079,2.601z M10.997,14.159
			c-0.001-0.042-0.005-0.085-0.006-0.128c0.007-0.134,0.011-0.266,0.011-0.4l0,0c-0.004-4.173-3.578-7.55-7.983-7.546H2.504
			l13.468-5.243L10.997,14.159z"/>
    <path fill="#818285" d="M69.219,7.543h3.579V5.959c0-4.11-3.332-7.442-7.444-7.442h-0.918c-4.109,0-7.444,3.332-7.444,7.442v5.1
			v5.101c0,4.108,3.335,7.44,7.444,7.44h0.918c4.112,0,7.444-3.332,7.444-7.44v-1.586h-3.579v1.586c0,2.132-1.729,3.863-3.865,3.863
			h-0.918c-2.137,0-3.867-1.732-3.867-3.863v-5.101v-5.1c0-2.135,1.73-3.865,3.867-3.865h0.918c2.137,0,3.865,1.73,3.865,3.865
			V7.543z"/>
    <path fill="#818285" d="M101.168,7.543h3.581V5.959c0-4.11-3.335-7.442-7.444-7.442h-0.92c-4.109,0-7.442,3.332-7.442,7.442v5.1
			v5.101c0,4.108,3.333,7.44,7.442,7.44h0.92c4.109,0,7.444-3.332,7.444-7.44v-1.586h-3.581v1.586c0,2.132-1.729,3.863-3.863,3.863
			h-0.92c-2.137,0-3.865-1.732-3.865-3.863v-5.101v-5.1c0-2.135,1.729-3.865,3.865-3.865h0.92c2.135,0,3.863,1.73,3.863,3.865V7.543
			z"/>
    <path fill="#818285" d="M77.079-1.474c-1.4,0-2.099,0.67-2.099,2.009v10.522v10.523c0,1.336,0.698,2.008,2.099,2.008h10.555
			v-1.572v-2.009h-1.848h-7.225v-7.376h6.393v-1.573V9.486h-6.393V2.109h7.225h1.848V0.099v-1.572H77.079z"/>
	</g>
  <g>
		<path d="M105.828,1.359c0-0.799,0.261-1.472,0.786-2.02c0.523-0.548,1.161-0.822,1.914-0.822c0.746,0,1.382,0.274,1.908,0.822
			c0.525,0.547,0.786,1.219,0.786,2.02c0,0.801-0.261,1.476-0.788,2.027c-0.526,0.55-1.16,0.826-1.906,0.826
			c-0.751,0-1.388-0.276-1.914-0.826C106.089,2.834,105.828,2.16,105.828,1.359z M106.285,1.359c0,0.674,0.218,1.242,0.652,1.701
			c0.438,0.459,0.968,0.69,1.591,0.69c0.617,0,1.146-0.23,1.582-0.691c0.436-0.462,0.653-1.029,0.653-1.7
			c0-0.669-0.216-1.233-0.65-1.69c-0.436-0.456-0.966-0.686-1.585-0.686c-0.623,0-1.153,0.229-1.591,0.686
			C106.503,0.126,106.285,0.689,106.285,1.359z M107.973,1.644v1.284h-0.564v-3.23h1.063c0.387,0,0.688,0.083,0.907,0.248
			c0.22,0.167,0.331,0.41,0.331,0.729c0,0.156-0.042,0.294-0.128,0.413c-0.08,0.119-0.203,0.218-0.365,0.295
			c0.169,0.067,0.292,0.166,0.362,0.301c0.073,0.135,0.113,0.297,0.113,0.486v0.213c0,0.104,0.004,0.199,0.018,0.281
			c0.008,0.082,0.022,0.15,0.05,0.203v0.06h-0.583c-0.021-0.052-0.035-0.131-0.04-0.233c-0.007-0.103-0.009-0.208-0.009-0.314V2.176
			c0-0.183-0.042-0.315-0.129-0.403c-0.082-0.086-0.224-0.129-0.416-0.129H107.973z M107.973,1.149h0.579
			c0.166-0.002,0.307-0.042,0.42-0.123c0.116-0.08,0.174-0.191,0.174-0.333c0-0.185-0.048-0.317-0.148-0.39
			c-0.103-0.075-0.273-0.113-0.525-0.113h-0.499V1.149z"/>
	</g>
</g>
</svg>
              </div>
            </div>
            <div v-if="settings.is_enable_bytepay" class="cc-button-container" @click="onCheckoutCC">
              <img src="vendor/aepay/images/jcb@2x.png" alt="">
              <img src="vendor/aepay/images/mastercard@2x.png" alt="">
              <img src="vendor/aepay/images/visa@2x.png" alt="">
            </div>
            <div v-if="settings.is_enable_paypal" style="margin-top: 20px">
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
import Loading from "../../components/loading";

export default {
  name: "aesport",
  components: {Loading},
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

.loading {
  &-container {
    position: relative;
  }

  &-dot {
    position: absolute;
    top: 50%;
    left: 50%;
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
  align-items: center;
  gap: 5px;

  img {
    width: 90px;
    object-fit: cover;
  }

  .text {
    font-size: 14px;
    font-weight: 400;
    color: black;
    margin: 0 4px;
  }

  @media only screen and (max-width: 768px) {
    height: 35px;
    img {
      width: 55px;
    }
  }
}

.wp-button-container {
  font-size: 30px;
  color: black;
  font-weight: 900;
  gap: 5px;

  .text {
    font-size: 14px;
    font-weight: 400;
    margin-top: 3px;
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

  .text {
    margin: 0 4px;
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
  margin-top: 20px;

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
