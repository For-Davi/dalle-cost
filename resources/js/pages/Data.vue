<script setup>
import PanelLayout from '@/layout/PanelLayout.vue'

defineOptions({
  name: 'Data',
});
</script>

<template>
  <PanelLayout>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Dados</h1>
      <div class="bg-white p-6 rounded-lg shadow">
        <p>Aqui você pode gerenciar todos os dados do sistema.</p>
      </div>
    </div>
  </PanelLayout>
</template>