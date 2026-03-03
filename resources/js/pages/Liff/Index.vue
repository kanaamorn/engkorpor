<script setup lang="ts">
import { onMounted, computed } from 'vue'
import { useLiff } from '@/composables/useLiff'

const { init, login, logout,getOS, email, isReady, isLoggedIn, profile, error } = useLiff()

const userEmail = computed(() => email())

onMounted(async () => {
    // const body = document.body

    await init()
    if (isReady.value && !isLoggedIn.value) {
        console.log("LIFF is ready but not logged in.", "OS:", getOS())
        // switch (getOS()) {
        //     case "android": body.style.backgroundColor = "#d1f5d3"; break
        //     case "ios": body.style.backgroundColor = "#eeeeee"; break
        //     case "web": body.style.backgroundColor = "#f0f0f0"; break
        // }
        // login() // auto-redirect to LINE login



    }
})
</script>

<template>
    <div class="h-screen">
        <div v-if="!isReady">Loading LINE...</div>
        <div v-else-if="error">Error: {{ error }}</div>
        <div v-else-if="isLoggedIn && profile">
            <img class="w-32 h-auto" :src="profile.pictureUrl" />
            <h1>Hello, {{ profile.displayName }}</h1>
            <p class="text-3xl text-red-600">{{ profile.userId }}</p>
            <p class="text-3xl text-blue-500">{{ profile.statusMessage }}</p>
            <p class="text-3xl text-blue-500">{{ userEmail }}</p>
            <button @click="logout">Logout</button>
        </div>
        <div v-else>
            <button @click="login">Login</button>
        </div>
    </div>
</template>
