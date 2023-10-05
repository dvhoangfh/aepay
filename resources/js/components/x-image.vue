<template>
    <img src="{{ originUrl }}" />
</template>

<script>
    export default {
        name: "x-image",
        props : {
            originUrl : {
                type: String,
            }
        }
    }
</script>

<style scoped>

</style>
