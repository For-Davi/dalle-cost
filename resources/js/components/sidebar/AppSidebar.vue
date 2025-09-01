<script setup>
  import { Database, ChartColumnBig, Users } from 'lucide-vue-next'
  import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarTrigger,
  } from '@/components/ui/sidebar'
  import { Link, usePage } from '@inertiajs/vue3'
  import { computed } from 'vue'

  defineOptions({
    name: 'AppSidebar',
  })

  const items = [
    {
      title: 'Dashboard',
      url: '/panel/dashboard',
      icon: ChartColumnBig,
    },
    {
      title: 'Dados',
      url: '/panel/data',
      icon: Database,
    },
    {
      title: 'Usuários',
      url: '/panel/users',
      icon: Users,
    },
  ]

  const isActive = url => {
    return (
      currentRoute.value === url || currentRoute.value.startsWith(url + '/')
    )
  }

  const page = usePage()
  const currentRoute = computed(() => page.url)
</script>

<template>
  <Sidebar class="border-r h-screen">
    <!-- Adicione h-screen aqui -->
    <SidebarHeader class="p-4 border-b">
      <h2 class="text-xl font-semibold">Dalle Cost</h2>
      <p class="text-sm text-muted-foreground">Painel Administrativo</p>
    </SidebarHeader>
    <SidebarContent>
      <SidebarGroup>
        <SidebarGroupContent>
          <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
              <SidebarMenuButton asChild>
                <Link
                  :href="item.url"
                  class="flex items-center gap-2"
                  :class="
                    isActive(item.url)
                      ? 'font-bold text-primary'
                      : 'text-muted-foreground'
                  "
                >
                  <component
                    :is="item.icon"
                    class="w-4 h-4"
                    :class="isActive(item.url) ? 'text-primary' : ''"
                  />
                  <span class="text-base">{{ item.title }}</span>
                </Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
    </SidebarContent>
  </Sidebar>
</template>
