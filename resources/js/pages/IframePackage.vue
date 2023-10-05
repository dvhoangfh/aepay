<template>
    <component :is="site"/>
</template>

<script>
import aesport from "./sites/aesport";
import aehub from "./sites/aehub";

export default {
    name: "IframePackage",
    components: {
        aesport,
        aehub
    },
    setup() {
        const site = JSON.parse(Site)
        return {
            site
        }
    }
}
</script>
