<script>
export default {
    name: 'AppLoader'
}
</script>

<template>
    <div class="loader d-flex justify-content-center align-items-center">
        <h1>Loading...</h1>
    </div>
</template>

<style scoped lang="scss">
.loader {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba($color: #000000, $alpha: 0.5);
}
</style>