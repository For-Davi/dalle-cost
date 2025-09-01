<script setup>
import { Calendar, Home, Inbox, Search, Settings } from "lucide-vue-next"
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
} from "@/components/ui/sidebar"
import { Link } from '@inertiajs/vue3'

defineOptions({
  name: 'AppSidebar',
});

const items = [
  {
    title: "Dashboard",
    url: '/panel/dashboard',
    icon: Home,
  },
  {
    title: "Dados",
    url: "/panel/data",
    icon: Inbox,
  },
];
</script>

<template>
  <Sidebar class="border-r">
    <SidebarContent>
      <div class="p-4 border-b">
        <h2 class="text-xl font-semibold">Dalle Cost</h2>
      </div>
      <SidebarGroup>
        <SidebarGroupLabel>Navegação</SidebarGroupLabel>
        <SidebarGroupContent>
          <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
              <SidebarMenuButton asChild>
                <Link :href="item.url" class="flex items-center gap-2">
                  <component :is="item.icon" class="w-4 h-4" />
                  <span>{{ item.title }}</span>
                </Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
    </SidebarContent>
  </Sidebar>
</template>