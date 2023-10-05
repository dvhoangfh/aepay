<template>
    <div class="container-xl">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <h2 class="page-title detail-match-title">
                        <div class="home-team">
                            <img :src="match.home_team_image" alt="">
                            <span>{{ match.home_team_name }}</span>
                        </div>
                        <div class="vs">
                            VS
                        </div>
                        <div class="away-team">
                            <span>{{ match.away_team_name }}</span>
                            <img :src="match.away_team_image" alt="">
                        </div>
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header justify-content-center">
                            <h3 class="card-title alert-wait">
                                <div v-if="isWait" key="alert_wait">
                                    This match has not started yet.
                                    Please refresh link when match start
                                </div>
                                <div v-else key="links">
                                    <div v-if="links.length > 0" class="match_start">
                                        <a v-for="(link, key) in links"
                                           href="javascript:void(0)"
                                           class="btn btn-ghost-primary w-100 d-inline"
                                           :key="key"
                                           @click="changeLink(key)"
                                           :class="{'active' : key === indexLink}"
                                        >
                                            {{ link.title ? link.title + (key + 1) : 'SERVER ' + (key + 1) }}
                                        </a>
                                    </div>
                                </div>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="countdown" v-if="isWait">
                                <img :src="'/images/stadium5.jpg'" alt="">
                                <img :src="match.home_team_image" class="img-home-team" alt="">
                                <img :src="match.away_team_image" class="img-away-team" alt="">
                                <vue-countdown
                                    id="clock"
                                    v-if="isWait"
                                    :transform="transformSlotProps"
                                    :time="startTime"
                                    @end="handleEnd"
                                    v-slot="{ days, hours, minutes, seconds }"
                                >
                                    The match will start in <span v-if="days > 0">{{ days }} days</span> {{ hours }}:{{ minutes }}:{{ seconds }}
                                </vue-countdown>
                            </div>
                            <div id="box-login" class="b2050468d text-center" v-if="isShowBanner">
                                <div id="d0f1633c6e084b2050468d00a9d5a294" class="c6e068d00a9d5a294" data-63466a0a="568549685680994-63466a0a09dfd03433gf32">
                                    <h4 style="margin-top: 17em;">Please login to disable Popup and view Fullscreen</h4>
                                    <a class="btn btn-primary" style="" href="/auth/login">Login</a>
                                </div>
                            </div>
                            <div class="iframe-player" id="box-video-play" v-if="!isWait">
                                <player :link="link" :customer="customers" ref="player" @event-play="handlePlay"></player>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12" id="related_matches">
                    <related-matches v-if="dataRelatedMatches.length" :related-matches="dataRelatedMatches"></related-matches>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import api from "../api";
    import RelatedMatches from "../components/related-matches";
    import Player from "../components/player";
    import PopupLogin from "../components/popup-login";
    import VueCountdown from "@chenfengyuan/vue-countdown"
    export default {
        name: "DetailMatch",
        components: {PopupLogin, Player, RelatedMatches, VueCountdown },
        data() {
            return {
                isWait : false,
                match: {},
                link: {},
                customers: {},
                dataRelatedMatches: [],
                matchId: parseInt(currentMatchId),
                indexLink: 0,
                clock: '',
                isShowBanner: false,
                interValBanner: '',
                startTime: parseInt(startTime)
            }
        },
        created() {
            this.getMatch()
            this.getUserInfo()
        },
        computed: {
            links() {
                let links = []
                if (this.match.links) {
                    links = this.match.links
                    this.link = links[this.indexLink]
                }
                return links
            },
            userIsLogin() {
                return Object.keys(this.customers).length
            }
        },
        mounted() {},
        unmounted() {
            clearInterval(this.interValBanner)
        },
        methods: {
            async getMatch() {
                try {
                    let payload = {
                        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
                    }
                    let response = await api.getDetailMatch(this.matchId, payload)
                    this.match = response.data.match
                    this.isWait = this.match.wait
                    this.dataRelatedMatches = response.data.related_matches
                } catch (e) {
                    console.log('e', e)
                }
            },
            async getUserInfo() {
                try {
                    let response = await api.getUserInfo()
                    this.customers = response.data
                } catch (e) {
                    console.log('e', e)
                }
            },
            changeLink(index) {
                this.indexLink = index
                this.link = this.links[this.indexLink]
            },
            destroy () {
                this.$refs.player.destroy()
            },
            handleEnd() {
                window.location.reload()
            },
            transformSlotProps(props) {
                const formattedProps = {};

                Object.entries(props).forEach(([key, value]) => {
                    formattedProps[key] = value < 10 ? `0${value}` : String(value);
                });

                return formattedProps;
            },
            handlePlay() {
                if (!this.userIsLogin) {
                    this.interValBanner = setInterval(function(){
                        this.showBanner()
                    }.bind(this), 3000);
                }
            },
            showBanner() {
                if (!this.userIsLogin) {
                    this.isShowBanner = true
                }
                let hiddenElements = $('.c6e068d00a9d5a294:hidden');
                let opacityElements = $('.c6e068d00a9d5a294').css('opacity');
                let visibleElement = $('.c6e068d00a9d5a294').css("visibility") === "hidden";
                let showBoxLogin = $('.b2050468d:visible');
                let hiddenBoxLogin = $('.b2050468d:hidden');
                let opacityBoxLogin = $('.b2050468d').css('opacity');
                let visibilityHiddenBoxLogin = $('.b2050468d').css("visibility") === "hidden";
                if (!this.userIsLogin && (hiddenElements.length > 0
                    || (opacityElements !== undefined && parseInt(opacityElements) === 0)
                    || visibleElement)
                ) {
                    if (showBoxLogin.length > 0) {
                        $('#box-login').html('<div style="background-color: #1f2936;\n' +
                            '        width: 1120px;\n' +
                            '        height: 530px;\n' +
                            '        position: absolute;\n' +
                            '        z-index: 99999;\n' +
                            '        bottom: 110px;\n' +
                            '        left: 73px;"' +
                            'data-63466a0a="568549685680994-63466a0a09dfd03433gf32"' +
                            'class="c6e068d00a9d5a294"' +
                            'data-v-63466a0a="">' +
                            '<h4 style="margin-top: 17em;">Please login to disable Popup and view Fullscreen</h4>\n' +
                            '<a class="btn btn-primary" style="" href="/auth/login">Login</a>' +
                            '</div>')
                    }
                }

                if (!this.userIsLogin
                    &&
                    (
                        (opacityBoxLogin !== undefined && parseInt(opacityBoxLogin) === 0)
                        || hiddenBoxLogin.length === 1
                        || visibilityHiddenBoxLogin
                    )
                ) {
                    clearInterval(this.interValBanner)
                    this.destroy()
                }

                // if (!this.userIsLogin && !this.first
                //     &&
                //     (
                //         showBoxLogin.length === 0
                //         && hiddenBoxLogin.length === 0
                //     )
                // ) {
                //     clearInterval(this.interValBanner)
                //     this.destroy()
                // }
                // this.first = false
            }
        }
    }
</script>

<style scoped lang="scss">
    .home-team {
        img {
            border-radius: 50%;
        }
    }
    .img-home-team {
        border-radius: 50%;
    }
    .away-team {
        img {
            border-radius: 50%;
        }
    }
    .img-away-team {
        border-radius: 50%;
    }
    #d0f1633c6e084b2050468d00a9d5a294 {
        background-color: #1f2936;
        width: 1120px;
        height: 530px;
        position: absolute;
        z-index: 99999;
        bottom: 110px;
        left: 73px;
    }
    @media only screen and (max-width: 640px) {
        #d0f1633c6e084b2050468d00a9d5a294 {
            background-color: #1f2936;
            width: 300px;
            height: 150px;
            position: absolute;
            z-index: 99999;
            bottom: 38px;
            left: 41px;
            h4 {
                margin-top: 2em !important;
            }
        }
    }
</style>
