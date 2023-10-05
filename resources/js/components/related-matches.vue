<template>
    <div class="card box-league">
        <div class="card-header league-header">
            <h3 class="card-title league-title">Related live</h3>
            <h3 class="card-title league-title">{{ relatedTime }}</h3>
        </div>
        <div class="card-body list-matches">
            <div class="d-flex justify-content-between match home-page" v-for="match in relatedMatches" :key="match.id">
                <div class="time">
                    <span>{{ match.short_time }}</span>
                </div>
                <div class="lst-leagues">
                    <div class="item-leagues">
                        <div class="left-leagues box-leagues">
                            <div class="box-img">
                                <span><img :src="match.home_team_image"></span>
                            </div>
                            <span>{{ match.home_team_name }}</span>
                        </div>
                        <div class="right-leagues box-leagues">
                            <div class="box-img">
                                <span><img :src="match.away_team_image"></span>
                            </div>
                            <span>{{ match.away_team_name }}</span>
                        </div>
                    </div>
                </div>
                <div class="box-play">
                    <a href="#" class="btn btn-secondary btn-live" v-if="match.wait">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-play" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M7 4v16l13 -8z"></path>
                        </svg>
                        Live
                    </a>
                    <a :href="match.url" class="btn btn-youtube btn-live" v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-play" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M7 4v16l13 -8z"></path>
                        </svg>
                        Live
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "related-matches",
        props: {
            relatedMatches: {
                type: Array,
                default: []
            }
        },
        computed: {
            relatedTime() {
                if (this.relatedMatches && this.relatedMatches.length) {
                    return this.relatedMatches[0].short_date
                }
            }
        }
    }
</script>

<style scoped>

</style>
