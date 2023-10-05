require('./bootstrap');

import {createApp} from 'vue'
import DetailMatch from "./pages/DetailMatch";
import Package from "./pages/Package";
import IframePackage from "./pages/IframePackage";

// const detailMatch = createApp(DetailMatch)
// detailMatch.mount('#detail-match')

const packagePage = createApp(Package)
packagePage.mount('#package-page')

const iframePage = createApp(IframePackage)
iframePage.mount('#iframe-package-page')
