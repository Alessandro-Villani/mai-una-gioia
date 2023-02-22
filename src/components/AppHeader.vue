<script>
export default {
    name: 'AppHeader'
}
</script>

<template>
    <header class="d-flex justify-content-center align-items-center">
        <nav class="container d-flex justify-content-center align-items-center">
            <a class="d-flex align-items-center" href="#"><img class="me-3" src="../assets/img/MUG2.png" alt="logo">
                <h1>MAI UNA GIOIA!</h1>
            </a>

        </nav>
        <p class="text-white me-3 mb-0">beta 0.3.0</p>
    </header>
</template>

<style scoped lang="scss">
@use '../assets/styles/partials/variables' as *;

header {
    height: 60px;
    background-color: $secondary-background;

    a {
        color: currentColor;
        text-decoration: none;
        height: 40px;

        img {
            max-height: 100%;
        }

        h1 {
            color: $logo;
            font-size: 28px;
        }
    }


}
</style>