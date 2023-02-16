<script>
import axios from 'axios'
import CharacterDetails from './CharacterDetails.vue';

export default {
    data() {
        return {
            character: [],
            isShown: false,
            isLoading: false,
        };
    },
    props: {
        characterData: Object,
        index: Number,
        currentIndex: Number
    },
    emits: ['index'],
    computed: {
        classImageUrl() {
            const imgName = this.characterData.class.toLowerCase().replace(" ", "");
            return new URL(`../assets/img/crests/${imgName}.png`, import.meta.url).href;
        },
        raceImageUrl() {
            const race = this.characterData.race.toLowerCase().replace(" ", "");
            const gender = this.characterData.gender.toLowerCase().replace(" ", "");
            const imgName = race + "-" + gender;
            return new URL(`../assets/img/races/${imgName}.webp`, import.meta.url).href;
        },
        colorClass() {
            return this.characterData.class.toLowerCase().replace(" ", "");
        },
    },
    methods: {
        fetchCharacter() {
            if (this.character.length !== 0 && !this.character.error) return
            axios.get(`http://armory.warmane.com/api/character/${this.characterData.name}/Icecrown/summary`).then(res => {
                this.isLoading = true
                this.character = res.data;
                if (this.character.error) {
                    const callInterval = setInterval(() => {
                        axios.get(`http://armory.warmane.com/api/character/${this.characterData.name}/Icecrown/summary`).then(res => {
                            this.character = res.data;
                            if (!this.character.error) clearInterval(callInterval);
                        }).catch(e => { console.log(e) }).then(() => {
                            if (!this.character || !this.character.error) this.isLoading = false;
                        })
                    }, 2000)
                }
            }).catch(e => { console.log(e) }).then(() => {
                if (this.character && !this.character.error) this.isLoading = false;
            })
        },
        cardClick() {
            if (this.index !== this.currentIndex) this.isShown = false;
            this.$emit('index', this.index);
            this.isShown = !this.isShown;
            this.fetchCharacter();
        }

    },

    components: { CharacterDetails }
}
</script>

<template>
    <div class="card p-3 px-5 mb-3" @click="cardClick">
        <div class="row row-cols-5 align-items-center text-center mb-2">
            <div class="col">
                <h6 class="mb-0" :class="colorClass">{{ characterData.name }}</h6>
            </div>
            <div class="col text-center d-flex flex-column align-items-center justify-content-center">
                <div>
                    <figure class="d-flex align-items-center justify-content-center m-0">
                        <img class="class" :src="classImageUrl" :alt="characterData.class">
                    </figure>
                    <h6 class="mb-0" :class="colorClass">{{ characterData.class }}</h6>
                </div>
            </div>
            <div class="col d-flex flex-column align-items-center justify-content-center">
                <div>
                    <figure class="d-flex align-items-center justify-content-center m-0">
                        <img class="race" :src="raceImageUrl" :alt="characterData.race">
                    </figure>
                    <h6 class="mb-0">{{ characterData.race }}</h6>
                </div>
            </div>
            <div class="col">
                <h6 class="mb-0">{{ characterData.level }}</h6>
            </div>
            <div class="col d-flex justify-content-center align-items-center">
                <div class="status" :class="characterData.online ? 'online' : 'offline'"></div>
            </div>
        </div>
        <font-awesome-icon icon="fa-solid fa-chevron-down"
            :class="{ 'rotate mb-5': this.index === currentIndex && isShown }" />
        <CharacterDetails v-show="isShown && index === currentIndex" :characterData="this.character"
            :isLoading="isLoading" />
</div>
</template>

<style scoped lang="scss">
@use '../assets/styles/partials/variables' as *;

.card {
    background-color: transparent;
    cursor: pointer;
    position: relative;
    transition: all 0.5s;

    &:hover {
        scale: 1.05;
        box-shadow: 0 0 10px $alliance-blue;
    }

    figure {
        width: 80px;
        height: 80px;
    }

    .class {
        width: 80px;
        height: 80px;
    }

    .race {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .fa-chevron-down {
        color: $alliance-blue;
        filter: brightness(2);
        opacity: 0;
        transition: all 0.5s;

        &.rotate {
            rotate: 180deg;
        }
    }

    &:hover .fa-chevron-down {
        opacity: 1;
    }

}

.status {
    width: 15px;
    height: 15px;
    border: 1px solid black;
    border-radius: 50%;

    &.online {
        background-color: greenyellow;
    }

    &.offline {
        background-color: brown;
    }
}
</style>