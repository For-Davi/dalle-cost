<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'

  defineOptions({
    name: 'Panel',
  })
</script>

<template>
  <PanelLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Total de Usuários</h3>
          <p class="text-3xl font-bold">1,248</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Faturamento Mensal</h3>
          <p class="text-3xl font-bold">R$ 24,800</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Novos Pedidos</h3>
          <p class="text-3xl font-bold">42</p>
        </div>
      </div>
    </div>
  </PanelLayout>
</template>
