<script setup>
  import PanelLayout from '@/layout/PanelLayout.vue'
  import { Bar } from 'vue-chartjs'
  import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
  } from 'chart.js'
  import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
  } from '@/components/ui/select'
  import { ref } from 'vue'

  ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
  )

  const filters = ref({
    memberID: null,
    originID: null,
    categoryID: null,
    year: null,
  })
  const chartData = ref({
    labels: [
      'Janeiro',
      'Fevereiro',
      'Março',
      'Abril',
      'Maio',
      'Junho',
      'Julho',
      'Setembro',
      'Outubro',
      'Novembro',
      'Dezembro',
    ],
    datasets: [
      {
        label: 'Dívidas',
        data: [40, 55, 32, 80, 60],
        backgroundColor: '#3b82f6',
      },
    ],
  })

  const chartOptions = ref({
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Relatório de divídas',
      },
    },
  })
</script>
<template>
  <PanelLayout>
    <section class="px-6 mt-2">
      <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
      <div class="flex gap-x-2 bg-white p-4 rounded-lg shadow">
        <div class="flex flex-col space-y-1.5">
          <Label>Devedor</Label>
          <Select v-model="filters.memberID">
            <SelectTrigger>
              <SelectValue placeholder="Selecione um devedor" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem :value="null">Todos</SelectItem>
                <SelectItem
                  v-for="(item, index) in []"
                  :value="item.id"
                  :key="index"
                  >{{ item.name }}</SelectItem
                >
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label>Origem</Label>
          <Select v-model="filters.originID">
            <SelectTrigger>
              <SelectValue placeholder="Selecione uma origem" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem :value="null">Todos</SelectItem>
                <SelectItem
                  v-for="(item, index) in []"
                  :value="item.id"
                  :key="index"
                  >{{ item.name }}</SelectItem
                >
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label>Categoria</Label>
          <Select v-model="filters.categoryID">
            <SelectTrigger>
              <SelectValue placeholder="Selecione uma categoria" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem :value="null">Todos</SelectItem>
                <SelectItem
                  v-for="(item, index) in []"
                  :value="item.id"
                  :key="index"
                  >{{ item.name }}</SelectItem
                >
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
        <div class="flex flex-col space-y-1.5">
          <Label>Ano</Label>
          <Select v-model="filters.year">
            <SelectTrigger>
              <SelectValue placeholder="Selecione um ano" />
            </SelectTrigger>
            <SelectContent>
              <SelectGroup>
                <SelectItem :value="null">{{
                  new Date().getFullYear().toString()
                }}</SelectItem>
                <SelectItem
                  v-for="(item, index) in []"
                  :value="item.id"
                  :key="index"
                  >{{ item.name }}</SelectItem
                >
              </SelectGroup>
            </SelectContent>
          </Select>
        </div>
      </div>
    </section>
    <section class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Mês atual</h3>
          <p class="text-3xl font-bold">R$ 1.248,00</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Total pago (mês atual)</h3>
          <p class="text-3xl font-bold">R$ 1.100,00</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
          <h3 class="text-lg font-semibold">Resultado</h3>
          <p class="text-3xl font-bold text-red-300">R$ - 148,00</p>
        </div>
      </div>
    </section>
    <section class="px-6">
      <div class="bg-white p-4 rounded-lg shadow">
        <Bar
          id="my-chart-id"
          :options="chartOptions"
          :data="chartData"
          style="height: 400px; max-height: 400px"
        />
      </div>
    </section>
  </PanelLayout>
</template>
