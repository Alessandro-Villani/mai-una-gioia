<script>
import AppHeader from './components/AppHeader.vue';
import AppLoader from './components/AppLoader.vue';
import GuildTable from './components/GuildTable.vue';

export default {
  name: "MaiUnaGioia",
  data() {
    return {
      isLoading: false,
      onlineCharacters: undefined
    }
  },
  components: { AppHeader, GuildTable, AppLoader },
  methods: {
    getLoadingStatus(status) {
      console.log(status);
      this.isLoading = status;
    },
    getOnlineCharacters(number) {
      this.onlineCharacters = number
    }
  }
}
</script>

<template>
  <AppHeader />
  <main class="text-center py-5">
    <h4 class="mb-4">Online: {{ onlineCharacters }}</h4>
    <AppLoader v-if="isLoading" />
    <GuildTable v-else @is-loading="getLoadingStatus" @online-characters="getOnlineCharacters" />
  </main>
</template>

<style lang="scss">
@use './assets/styles/style.scss'
</style>
