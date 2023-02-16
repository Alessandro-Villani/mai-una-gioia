<script>
import AppLoader from './AppLoader.vue';
import items from '../../../data.json';


export default {
    name: "CharacterDetails",
    data() {
        return {
            items,
            slotValues: {
                Head: 1.0000,
                Neck: 0.5625,
                Shoulder: 0.7500,
                Back: 0.5625,
                Chest: 1.0000,
                Shirt: 0,
                Tabard: 0,
                Wrist: 0.5625,
                Hands: 0.7500,
                Waist: 0.7500,
                Legs: 1.0000,
                Feet: 0.7500,
                Finger: 0.5625,
                Trinket: 0.5625,
                'One-Hand': 1.0000,
                'Main Hand': 1.000,
                'Two-Hand': 2.000,
                Thrown: 0.3164,
                Relic: 0.3164,
                Weapon: 2.000,
                Ranged: 0.3164,
                'Off Hand': 1.0000,
                'Held In Off-hand': 1.0000

            },
            gsFormula: {
                A: {
                    Epic: { A: 91.4500, B: 0.6500 },
                    Rare: { A: 81.3750, B: 0.8125 },
                    Uncommon: { A: 73.0000, B: 1.0000 }
                },
                B: {
                    Epic: { A: 26.0000, B: 1.2000 },
                    Rare: { A: 0.7500, B: 1.8000 },
                    Uncommon: { A: 8.0000, B: 2.0000 },
                    Common: { A: 0.0000, B: 2.2500 }
                }
            },
            itemScores: []
        }
    },
    props: {
        characterData: Object,
        isLoading: Boolean,
    },
    computed: {
        raceImageUrl() {
            if (this.characterData.race) {
                const imgName = this.characterData.race.toLowerCase().replace(" ", "");
                return new URL(`../assets/img/races-banner/${imgName}.webp`, import.meta.url).href;
            } else return null;
        },
        equipmentIds() {
            if (!this.characterData.equipment) return null;
            const equipIds = [];
            this.characterData.equipment.forEach(equip => {
                equipIds.push(equip.item);
            });
            return equipIds
        },
        twoHandsQuantity() {
            if (!this.equipmentIds) return null;
            const equipTypes = [];
            this.equipmentIds.forEach(id => {
                if (this.getItem(parseInt(id)).slot === 'Two-Hand') equipTypes.push(this.getItem(parseInt(id)).slot);
            });
            return equipTypes.length;
        },
        gearScore() {
            if (!this.characterData.equipment) return null;
            const singleGs = []
            this.equipmentIds.forEach(id => {
                const itemLevel = this.getItem(parseInt(id)).itemLevel;
                const itemRarity = this.getItem(parseInt(id)).quality;
                const slotMOD = this.getItem(parseInt(id)).slot === 'Two-Hand' && this.twoHandsQuantity === 2 ? 1.000 : this.slotValues[this.getItem(parseInt(id)).slot];
                singleGs.push(this.calculateItemScore(itemLevel, itemRarity, slotMOD));
            });
            let gs = 0
            singleGs.forEach(item => {
                gs += item;
            })
            if (gs === 0) return 0
            else return gs || null;
        },
        gsColor() {
            let color = '#8C8C8C'
            if (this.gearScore >= 1000) color = '#FFFFFF'
            if (this.gearScore >= 2000) color = '#1FFF00'
            if (this.gearScore >= 3000) color = '#007FFF'
            if (this.gearScore >= 4000) color = '#AF47F7'
            if (this.gearScore >= 5000) color = '#f04c00'
            if (this.gearScore >= 6000) color = '#FF0000'
            if (this.gearScore = 0) color = '#E6CC80'
            return color

        },
        spec1ImgUrl() {
            if (this.characterData.talents) {
                const folderName = this.characterData.class.toLowerCase().replace(" ", "");
                const imgName = this.characterData.talents[0].tree.toLowerCase().replace(" ", "");
                return new URL(`../assets/img/spec/${folderName}/${imgName}.png`, import.meta.url).href;
            } else return null;
        },
        spec2ImgUrl() {
            if (this.characterData.talents && this.characterData.talents.length > 1) {
                const folderName = this.characterData.class.toLowerCase().replace(" ", "");
                const imgName = this.characterData.talents[1].tree.toLowerCase().replace(" ", "");
                return new URL(`../assets/img/spec/${folderName}/${imgName}.png`, import.meta.url).href;
            } else return null;
        },
        prof1ImgUrl() {
            if (this.characterData.professions) {
                const imgName = this.characterData.professions[0].name;
                return new URL(`../assets/img/professions/${imgName}.jpg`, import.meta.url).href;
            } else return null;
        },
        prof2ImgUrl() {
            if (this.characterData.professions && this.characterData.professions.length > 1) {
                const imgName = this.characterData.professions[1].name;
                return new URL(`../assets/img/professions/${imgName}.jpg`, import.meta.url).href;
            } else return null;
        }
    },
    methods: {
        getItem(id) {
            return this.items.filter(item => {
                return item.itemId === id
            })[0]
        },
        getRarity(id) {
            return this.items.filter(item => {
                return item.itemId === id
            })[0].quality.toLowerCase();
        },
        calculateItemScore(itemLevel, itemRarity, slotMOD) {
            let qualityScale = 1;
            if (itemRarity === 'Legendary') qualityScale = 1.3;
            if (itemRarity === 'Common' || itemRarity === 'Poor') qualityScale = 0.005;
            let table = itemLevel > 120 ? this.gsFormula.A : this.gsFormula.B;
            console.log(table);
            let rarity = itemRarity === 'Legendary' ? 'Epic' : itemRarity === 'Common' || itemRarity === 'Poor' ? 'Uncommon' : itemRarity;
            let scale = 1.8618;
            let score = Math.floor(((itemLevel - table[rarity].A) / table[rarity].B) * slotMOD * scale * qualityScale);
            return score < 0 ? 0 : score;
        }
    },
    components: { AppLoader }
}
</script>

<template>
    <div class="details">
        <AppLoader v-if="isLoading" />
        <div class="row">
            <div class="col-4 d-flex flex-column align-items-center">
                <ul class="equip p-0 ps-5">
                    <li v-for="piece in characterData.equipment" :key="piece.item" class="d-flex align-items-center mb-2">
                        <a :class="getRarity(parseInt(piece.item))"
                            :href="`http://wotlk.cavernoftime.com/item=${piece.item}`"><img class="me-2"
                                :class="getRarity(parseInt(piece.item))"
                                :src="`https://wotlk.evowow.com/static/images/wow/icons/large/${getItem(parseInt(piece.item)).icon}.jpg`"
                                alt=""><span class="item-name">{{ piece.name }}</span></a>
                    </li>
                </ul>
            </div>
            <div class="race col-4 d-flex flex-column align-items-center justify-content-center">
                <h3 v-if="gearScore || gearScore === 0" class="mb-3" :style="{ color: `${gsColor}` }">GEARSCORE: {{
                    gearScore }}</h3>
                <img class="img-fluid" :src="raceImageUrl" alt="">
            </div>
            <div class="specs col-2 d-flex flex-column justify-content-center align-items-start text-start">
                <h5 v-if="spec1ImgUrl" class="mb-3 align-self-center">SPECS</h5>
                <div v-if="spec1ImgUrl" class="spec d-flex align-items-center mb-3">
                    <div class="me-2">
                        <p class="mb-2">{{ characterData.talents[0].tree }}</p>
                        <p v-if="characterData.talents[0].points" class="mb-0">{{ `${characterData.talents[0].points[0]} -
                                                    ${characterData.talents[0].points[1]} -
                                                    ${characterData.talents[0].points[2]}` }}</p>
                    </div>
                    <img class="img-fluid" :src="spec1ImgUrl" :alt="characterData.talents[0].tree">
                </div>
                <div v-if="spec2ImgUrl" class="spec d-flex align-items-center align-self-end">
                    <div class="me-2">
                        <p class="mb-2">{{ characterData.talents[1].tree }}</p>
                        <p class="mb-0">{{ `${characterData.talents[1].points[0]} -
                                                    ${characterData.talents[1].points[1]} -
                                                    ${characterData.talents[1].points[2]}` }}</p>
                    </div>
                    <img class="img-fluid" :src="spec2ImgUrl" :alt="characterData.talents[1].tree">
                </div>
            </div>
            <div class="professions col-2 d-flex flex-column justify-content-center align-items-start text-start">
                <h5 v-if="prof1ImgUrl" class="mb-3 align-self-center">PROF</h5>
                <div v-if="prof1ImgUrl" class="spec d-flex align-items-center align-self-end mb-3">
                    <img class="profession me-2" :src="prof1ImgUrl" :alt="characterData.professions[0].name">
                    <div>
                        <p class="mb-2">{{ characterData.professions[0].name }}</p>
                        <p class="mb-0">{{ characterData.professions[0].skill }}</p>
                    </div>
                </div>
                <div v-if="prof2ImgUrl" class="spec d-flex align-items-center">
                    <img class="profession me-2" :src="prof2ImgUrl" :alt="characterData.professions[1].name">
                    <div>
                        <p class="mb-2">{{ characterData.professions[1].name }}</p>
                        <p class="mb-0">{{ characterData.professions[1].skill }}</p>
                    </div>
                </div>
            </div>
        </div>
</div>
</template>

<style scoped lang="scss">
.details {
    position: relative;
}

img.profession {
    width: 64px;
    height: 64px;
    border: 2px outset gray;
}


ul {
    list-style: none;

    img {
        width: 30px;
        border: 2px outset;

        &.poor {
            border-color: #9d9d9d;
        }

        &.common {
            border-color: white;
        }

        &.uncommon {
            border-color: #1eff00;
        }

        &.rare {
            border-color: #0070dd;
        }

        &.epic {
            border-color: #a335ee;
        }

        &.legendary {
            border-color: #ff8000;
        }

        &.artifact {
            border-color: #e6cc80;
        }

        &.heirloom {
            border-color: #00ccff;
        }
    }

    .poor {
        color: #9d9d9d;
    }

    .common {
        color: white;
    }

    .uncommon {
        color: #1eff00;
    }

    .rare {
        color: #0070dd;
    }

    .epic {
        color: #a335ee;
    }

    .legendary {
        color: #ff8000;
    }

    .artifact {
        color: #e6cc80;
    }

    .heirloom {
        color: #00ccff;
    }

}
</style>