require('./bootstrap');

import {createApp} from 'vue'
import Package from "./pages/Package";
import IframePackage from "./pages/IframePackage";
import GgIframePackage from "./pages/GgIframePackage";

const packagePage = createApp(Package)
packagePage.mount('#package-page')

const iframePage = createApp(IframePackage)
iframePage.mount('#iframe-package-page')

const ggIframePage = createApp(GgIframePackage)
ggIframePage.mount('#gg-iframe-page')

