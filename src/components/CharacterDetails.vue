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
            }
            return null;
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
            return gs || null;
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
            <div class="col-5">
                <h3 v-if="gearScore" class="mb-3" :style="{ color: `${gsColor}` }">GEARSCORE: {{ gearScore }}</h3>
                <ul class="p-0">
                    <li v-for="piece in characterData.equipment" :key="piece.item">
                        <a :class="getRarity(parseInt(piece.item))"
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