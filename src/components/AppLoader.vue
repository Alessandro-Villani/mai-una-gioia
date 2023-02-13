<script>
export default {
    name: 'AppLoader',
    props: {
        overlay: Boolean,
    }
}
</script>

<template>
    <div class="loader d-flex justify-content-center align-items-center" :class="{ 'overlay': overlay }">
        <font-awesome-icon icon="fa-solid fa-circle-notch" class="fa-2x fa-spin me-2 mb-0" /><span>Loading... </span>
    </div>
</template>

<style scoped lang="scss">
@use '../assets/styles/partials/variables' as *;

.loader {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    color: $alliance-blue;
    filter: brightness(1.5);

    &.overlay {
        background-color: rgba($color: #000000, $alpha: 0.5);
    }

    span {
        line-height: 24px;
        font-size: 24px;
        font-weight: bold;
    }
}
</style>