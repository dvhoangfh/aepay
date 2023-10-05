<template>
    <div class="box-player">
        <video id="video" ref="videoPlayer" width=960 height=540 class="video-js vjs-default-skin" controls ></video>
        <img v-if="warning" src="/images/stadium.jpg" alt="">
    </div>
</template>

<script>
import api from "../api"
const TIME_OUT_POPUP = 15000 //15s
export default {
    name: "player",
    props: {
        link: {
            type: Object,
            default: {
                url: ''
            }
        },
        customer: {
            type: Object,
            default: {}
        },
    },
    data() {
      return {
          warning: false,
          player: null,
          modal: null,
          options: {
              liveui: true,
              autoplay: 'any',
              muted: true,
              controls: true,
              controlBar: {
                  fullscreenToggle: false,
                  pictureInPictureToggle: false,
                  textTrackSettings: false
              },
              playsinline: true
          }
      }
    },
    mounted() {
        if(this.link && this.link.url) {
            this.init(this.link ? this.link.url : '')
        }
    },
    methods: {
        init(url) {
            const player = videojs("video", this.options);
            player.src({
                src: url,
                type: "application/x-mpegURL",
            })
            player.on('error', function() {
                api.saveLog(player.error())
            });
            this.player = player
            setTimeout(() => this.showDialog(), TIME_OUT_POPUP);
        },
        showDialog() {
            if (Object.keys(this.customer).length === 0 || (this.customer && !this.customer.is_premium)) {
                const ModalDialog = videojs.getComponent('ModalDialog');
                let configs = {
                    temporary: false,
                    description: 'description',
                    label: "test",
                    uncloseable: true
                }
                let modal = new ModalDialog(this.player, configs);
                let content = '';
                if (Object.keys(this.customer).length === 0) {
                    content = '<div class="modal-popup-video">\n' +
                        '        <h4 class="modal-popup-video-title">Please login to disable Popup and view Fullscreen</h4><br/>\n' +
                        '        <a class="btn btn-warning" style="" href="/auth/login">Login</a>\n' +
                        '    </div>';
                } else if (this.customer && !this.customer.is_premium) {
                    content = '<div class="modal-popup-video">\n' +
                        '        <h4 class="modal-popup-video-title">Please subscription plan to disable Popup and view Fullscreen</h4><br/>\n' +
                        '        <a class="btn btn-warning" style="" href="/packages">Subscription</a>\n' +
                        '    </div>';
                }
                modal.on('modalopen', function() {
                    modal.contentEl().innerHTML = content
                });
                this.player.addChild(modal);
                modal.open()
            }
        },
        destroy() {
            this.player.dispose()
            this.warning = true
        }
    },
    watch: {
        'link.url': function (newVal, oldVal) {
            if (oldVal !== newVal) {
                this.init(newVal)
            }
        }
    }
}
</script>

<style scoped>

</style>
