<script>
export default {
    name: 'AppJumbotron',
    props: {
        onlineCharacters: Number
    }
}
</script>

<template>
    <div class="jumbotron mb-3">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-4 h-100 d-flex align-items-center justify-content-center">
                    <a class="btn btn-discord" href="https://discord.gg/GC5HHtmn"><font-awesome-icon
                            icon="fa-brands fa-discord" class="me-2" />Join our Discord</a>
                </div>

                <div class="col-4">

                </div>
                <div class="col-4 h-100 d-flex align-items-center justify-content-center flex-column">
                    <a class="btn btn-join" href="#">Join MAI UNA GIOIA!</a>
                    <h4 class="online">Online now: {{ onlineCharacters }}</h4>
                </div>
            </div>
        </div>
</div>
</template>

<style scoped lang="scss">
@use '../assets/styles/partials/variables' as *;

.jumbotron {
    background-image: url(../assets/img/mug_gif.gif);
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    height: 450px;
    position: relative;

    .btn-discord {
        background-color: rgb(81, 101, 246);
        color: white;
        font-weight: bold;
        transition: all 0.5s;

        &:hover {
            background-color: rgb(157, 168, 255);
        }
    }

    .btn-join {
        background-color: rgb(253, 199, 248);
        color: $alliance_blue;
        font-weight: bold;
        transition: all 0.5s;

        &:hover {
            background-color: rgb(250, 222, 247);
            color: orange;
        }
    }

    h4 {
        position: absolute;
        transform: translateY(200%);
    }
}
</style>