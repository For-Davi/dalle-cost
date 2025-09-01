<script setup>
import AppSidebar from '@/components/sidebar/AppSidebar.vue'
import { SidebarProvider } from '@/components/ui/sidebar'

defineOptions({
  name: 'PanelLayout',
});
</script>

<template>
  <SidebarProvider>
    <div class="flex h-screen w-screen bg-gray-50">
      <AppSidebar />
      <main class="flex-1 overflow-auto">
        <slot />
      </main>
    </div>
  </SidebarProvider>
</template>