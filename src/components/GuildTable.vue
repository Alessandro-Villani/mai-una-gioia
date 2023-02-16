<script>
import axios from 'axios'
import CharacterRow from './CharacterRow.vue';
import RosterComposition from './RosterComposition.vue';

export default {
    name: "GuildTable",
    data() {
        return {
            guildRoster: [],
            status: '',
            nameFilter: '',
            classes: [],
            classFilter: '',
            races: [],
            raceFilter: '',
            levelFilter: null,
            onlineCharacters: undefined,
            currentIndex: null
        };
    },
    emits: ['is-loading'],
    computed: {
        filteredRoster() {
            let filteredRoster = this.guildRoster
            if (this.nameFilter) {
                filteredRoster = filteredRoster.filter(character => {
                    return character.name.toLowerCase().includes(this.nameFilter.toLowerCase());
                })
            }
            if (this.classFilter) {
                filteredRoster = filteredRoster.filter(character => {
                    return character.class.includes(this.classFilter);
                })
            }
            if (this.raceFilter) {
                filteredRoster = filteredRoster.filter(character => {
                    return character.race.includes(this.raceFilter);
                })
            }
            if (this.levelFilter) {
                filteredRoster = filteredRoster.filter(character => {
                    return character.level === this.levelFilter.toString();
                })
            }
            return filteredRoster
        }
    },
    methods: {
        fetchCharacters() {
            axios.get("https://armory.warmane.com/api/guild/Mai+Una+Gioia/Icecrown/summary").then(res => {
                this.guildRoster = res.data.roster;
                this.getAll('class', this.classes);
                this.getAll('race', this.races);
                this.onlineCharacters = this.guildRoster.filter(character => {
                    return character.online;
                }).length;
                this.$emit('online-characters', this.onlineCharacters);
                if (this.status === 'online') {
                    const roster = res.data.roster;
                    console.log('roster');
                    console.log(roster);
                    this.guildRoster = roster.filter(character => {
                        return character.online;
                    })
                }
                if (this.status === 'offline') {
                    const roster = res.data.roster
                    console.log('roster');
                    console.log(roster);
                    this.guildRoster = roster.filter(character => {
                        return !character.online;
                    })
                }
            }).catch(e => console.log(e)).then(() => {
                console.log('here')
            });
        },
        getAll(param, destination) {
            this.guildRoster.forEach(character => {
                if (!destination.includes(character[param])) destination.push(character[param]);
            });
            this.classes.sort();
        },
        getIndex(index) {
            this.currentIndex = index;
        },
        changeClassFilter(filter) {
            this.classFilter = filter;
        },
        setLevelFilter(bool) {
            this.levelFilter = bool ? 80 : null;
        }
    },
    mounted() {
        this.fetchCharacters();
    },
    components: { CharacterRow, RosterComposition }
}
</script>

<template>
    <RosterComposition :guildRoster="guildRoster" :classes="classes" @class-select="changeClassFilter"
        @only-80="setLevelFilter" />
    <div class="container">
        <div class="row row-cols-5 text-center mb-3 px-3">
            <div class="col">
                <h5 class="mb-2">NOME</h5>
                <input type="text" v-model="nameFilter">
            </div>
            <div class="col">
                <h5 class="mb-2">CLASSE</h5>
                <select id="character-class" v-model="classFilter">
                    <option value="">--No Filter--</option>
                    <option v-for="(classname, i) in classes" :key="i">{{ classname }}</option>
                </select>
            </div>
            <div class="col">
                <h5 class="mb-2">RAZZA</h5>
                <select id="character-class" v-model="raceFilter">
                    <option value="">--No Filter--</option>
                    <option v-for="(race, i) in races" :key="i">{{ race }}</option>
                </select>
            </div>
            <div class="col">
                <h5 class="mb-2">LIVELLO</h5>
                <div class="level-group d-flex align-items-center justify-content-center">
                    <input class="level" type="number" min="1" max="80" v-model="levelFilter">
                    <button class="reset-level btn btn-danger" @click="levelFilter = null"><font-awesome-icon
                            icon="fa-solid fa-circle-minus" /></button>
                </div>
            </div>
            <div class="col">
                <h5 class="mb-2">STATUS</h5>
                <select id="online" v-model="status" @change="fetchCharacters()">
                    <option value="">--No Filter--</option>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                </select>
            </div>
        </div>
        <CharacterRow v-for="(character, i) in filteredRoster" :key="character.name" :characterData="character" :index="i"
            :currentIndex="currentIndex" @index="getIndex" />
</div>
</template>

<style scoped lang="scss">
@use '../assets/styles/partials/variables' as *;

select,
input {
    background-color: $secondary-background;
    color: white;
    border-radius: 5px;
    padding: 2px;
    font-size: 12px;
    text-align: center;
    border: 2px solid $alliance-blue;

    &.level {
        border-radius: 5px 0px 0 5px;
    }
}

.reset-level {
    border-radius: 0 5px 5px 0;
    padding: 2px 5px;
    font-size: 12px;
    border: 2px solid $alliance-blue;
    border-left: none;
    transition: all 0.5s;

    &:hover {
        border-color: $alliance-blue;
        background-color: $alliance-blue;
        color: rgb(220, 53, 69);
    }
}
</style>