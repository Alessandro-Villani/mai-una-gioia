<script>
export default {
    name: 'RosterComposition',
    data() {
        return {
            isShown: true,
            only80: false
        }
    },
    props: {
        guildRoster: Array,
        classes: Array
    },
    emits: ['class-select', 'only-80'],
    methods: {
        properClassName(name) {
            return name.toLowerCase().replace(" ", "");
        },
        classQuantity(className) {
            let memberCount = this.guildRoster.filter(char => {
                return char.class === className;
            })
            console.log(memberCount);
            if (this.only80) {
                memberCount = memberCount.filter(char => {
                    return char.level === '80';
                })
            }
            return memberCount.length;
        },
        classColumnHeight(className) {
            return Math.ceil((1000 / this.guildRoster.length) * this.classQuantity(className));
        },
        toggleGraph() {
            this.isShown = !this.isShown;
        }
    }
}
</script>

<template>
    <div class="container mb-5">
        <div class="roster-title d-flex justify-content-center align-items-center mb-3" @click="toggleGraph">
            <h5 class="mb-0 me-3">Roster Composition</h5>
            <font-awesome-icon icon="fa-solid fa-chevron-down" :class="{ 'rotated': !isShown }" />
        </div>
        <div v-if="isShown" class="class-graph py-3">
            <div class="checkbox-group d-flex justify-content-center align-items-center mb-3">
                <label for="only-max" class="me-2">80 only</label>
                <input type="checkbox" id="only-max" v-model="only80" @change="$emit('only-80', only80)">
            </div>
            <div class="d-flex justify-content-center align-items-stretch mb-3">
                <div v-for="className in classes" :key="className"
                    class="class-group d-flex flex-column align-items-center align-self-end me-2"
                    @click="$emit('class-select', className)">
                    <p class="mb-2">{{ classQuantity(className) }}</p>
                    <div :class="properClassName(className)" class="classcolumn mb-2"
                        :style="`height : ${classColumnHeight(className)}px`"></div>
                    <p class="mb-0" :class="properClassName(className)">{{ className === 'Death Knight' ? 'DK' :
                        className }}
                    </p>
                </div>
            </div>
        </div>
</div>
</template>

<style scoped lang="scss">
@use '../assets/styles/partials/variables' as *;

.roster-title {
    cursor: pointer;

    .fa-chevron-down {
        transform: rotate(180deg);
        transition: all 0.5s;

        &.rotated {
            transform: rotate(0deg);
        }
    }
}

.class-graph {
    border: 1px solid rgba(0, 0, 0, 0.175);
    border-radius: 0.375rem;
}

.class-group {
    width: 80px;
    text-shadow: 0 0 10px black;
    cursor: pointer;
    transition: all 0.5s;

    &:hover {
        scale: 1.1;
    }

    .classcolumn {
        width: 30px;
        border-radius: 2px;
        box-shadow: 0 0 5px black;

        &.deathknight {
            background-color: $dk;
        }

        &.druid {
            background-color: $druid;
        }

        &.hunter {
            background-color: $hunter;
        }

        &.mage {
            background-color: $mage;
        }

        &.paladin {
            background-color: $paladin;
        }

        &.priest {
            background-color: $priest;
        }

        &.rogue {
            background-color: $rogue;
        }

        &.shaman {
            background-color: $shaman;
        }

        &.warlock {
            background-color: $warlock;
        }

        &.warrior {
            background-color: $warrior;
        }
    }
}
</style>