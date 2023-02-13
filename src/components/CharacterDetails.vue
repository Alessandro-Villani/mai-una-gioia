<script>
import AppLoader from './AppLoader.vue';



export default {
    name: "CharacterDetails",
    props: {
        characterData: Object,
        isLoading: Boolean,
    },
    computed: {
        raceImageUrl() {
            if (this.characterData.race) {
                const imgName = this.characterData.race.toLowerCase().replace(" ", "");
                console.log(imgName);
                return new URL(`../assets/img/races-banner/${imgName}.webp`, import.meta.url).href;
            }
            return null;
        },
    },
    components: { AppLoader }
}
</script>

<template>
    <div class="details">
        <AppLoader v-if="isLoading" />
        <div class="row">
            <div class="col-5">
                <ul>
                    <li v-for="piece in characterData.equipment"><a
                            :href="`http://wotlk.cavernoftime.com/item=${piece.item}`">{{ piece.name }}</a>
                    </li>
                </ul>
            </div>
            <div class="col-2 d-flex align-items-center">
                <img class="img-fluid" :src="raceImageUrl" alt="">
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.details {
    position: relative;
}

ul {
    list-style: none;
}
</style>