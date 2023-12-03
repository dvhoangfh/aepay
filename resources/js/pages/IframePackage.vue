<template>
    <component :is="site"/>
</template>

<script>
import aesport from "./sites/aesport";

export default {
    name: "IframePackage",
    components: {
        aesport,
    },
    setup() {
        const site = 'aesport'
        return {
            site
        }
    }
}
</script>
