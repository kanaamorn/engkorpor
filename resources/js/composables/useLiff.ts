import liff from '@line/liff'
import { ref } from 'vue'

const isReady = ref(false)
const isLoggedIn = ref(false)
const profile = ref<{ userId: string; displayName: string; pictureUrl?: string; statusMessage?: string} | null>(null)
const error = ref<string | null>(null)

export function useLiff() {
  const liffId = import.meta.env.VITE_LIFF_ID as string

  const init = async () => {
    try {
      await liff.init({ liffId })
      isReady.value = true
      isLoggedIn.value = liff.isLoggedIn()

      if (isLoggedIn.value) {
        profile.value = await liff.getProfile()
      }
    } catch (e: any) {
      error.value = e.message
    }
  }

  const login = () => liff.login()
  const logout = () => { liff.logout(); isLoggedIn.value = false }
  const getIdToken = () => liff.getIDToken()
  const getAccessToken = () => liff.getAccessToken()
  const email = () => liff.getDecodedIDToken()?.email
  const getOS = () => liff.getOS()

  return { init, login, logout, getIdToken, getAccessToken, getOS, email, isReady, isLoggedIn, profile, error }
}
