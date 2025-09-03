<script setup>
  import AppSidebar from '@/components/sidebar/AppSidebar.vue'
  import { SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar'
  import { Toaster } from '@/components/ui/sonner'
  import 'vue-sonner/style.css'

  defineOptions({
    name: 'PanelLayout',
  })
</script>

<template>
  <SidebarProvider>
    <Toaster class="pointer-events-auto" />
    <div class="flex h-screen w-screen bg-gray-50">
      <AppSidebar />
      <main class="flex-1 overflow-auto">
        <SidebarTrigger />
        <slot />
      </main>
    </div>
  </SidebarProvider>
</template>
