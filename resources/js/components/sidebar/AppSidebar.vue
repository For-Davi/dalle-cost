<script setup>
  import { Database, ChartColumnBig, Users, LogOut } from 'lucide-vue-next'
  import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarHeader,
    SidebarTrigger,
    SidebarFooter,
  } from '@/components/ui/sidebar'
  import { Link, usePage, router } from '@inertiajs/vue3'
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
  const handleLogout = () => {
    router.post(route('logout'))
  }

  const page = usePage()
  const currentRoute = computed(() => page.url)
</script>

<template>
  <Sidebar class="border-r h-screen flex flex-col">
    <SidebarHeader class="p-4 border-b">
      <h2 class="text-xl font-semibold">Dalle Cost</h2>
      <p class="text-sm text-muted-foreground">Painel Administrativo</p>
    </SidebarHeader>

    <SidebarContent class="flex-1">
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
    <SidebarFooter class="p-4 border-t">
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton asChild class="text-red-600" @click="handleLogout">
            <button class="flex items-center gap-2 w-full cursor-pointer">
              <LogOut class="w-4 h-4" />
              <span class="text-base font-medium">Sair</span>
            </button>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarFooter>
  </Sidebar>
</template>
