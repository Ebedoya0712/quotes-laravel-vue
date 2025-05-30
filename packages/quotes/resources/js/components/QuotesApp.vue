<template>
  <div class="quotes-container">
    <h1>📚 Quotes App</h1>
    <div class="actions">
      <button @click="fetchAll">View All</button>
      <button @click="fetchRandom">Random</button>
      <input v-model="quoteId" type="number" min="1" placeholder="Quote ID" />
      <button @click="fetchById">Search by ID</button>
    </div>
    <div v-if="loading" class="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>
    <div v-if="quotes.length" class="quotes-list">
      <h2>All Quotes</h2>
      <ul>
        <li v-for="q in paginatedQuotes" :key="q.id">
          <span class="quote-text">"{{ q.quote }}"</span>
          <span class="quote-author">- {{ q.author }}</span>
        </li>
      </ul>
      <div class="pagination" v-if="totalPages > 1">
        <button :disabled="page === 1" @click="page--">Previous</button>
        <span>Page {{ page }} of {{ totalPages }}</span>
        <button :disabled="page === totalPages" @click="page++">Next</button>
      </div>
    </div>
    <div v-if="quote" class="single-quote">
      <h2>Quote</h2>
      <blockquote>
        <p>"{{ quote.quote }}"</p>
        <footer>- {{ quote.author }}</footer>
      </blockquote>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

interface Quote {
  id: number
  quote: string
  author: string
}

const quotes = ref<Quote[]>([])
const quote = ref<Quote | null>(null)
const quoteId = ref<number | null>(null)
const loading = ref(false)
const error = ref('')
const page = ref(1)
const perPage = 5

const paginatedQuotes = computed(() => {
  const start = (page.value - 1) * perPage
  return quotes.value.slice(start, start + perPage)
})

const totalPages = computed(() => Math.ceil(quotes.value.length / perPage))

const fetchAll = async () => {
  loading.value = true
  error.value = ''
  quote.value = null
  try {
    const res = await fetch('/api/quotes')
    const data = await res.json()
    quotes.value = data
    page.value = 1
  } catch (e) {
    error.value = 'Failed to load quotes'
  }
  loading.value = false
}

const fetchRandom = async () => {
  loading.value = true
  error.value = ''
  quotes.value = []
  try {
    const res = await fetch('/api/quotes/random')
    quote.value = await res.json()
  } catch (e) {
    error.value = 'Failed to load random quote'
  }
  loading.value = false
}

const fetchById = async () => {
  if (!quoteId.value) return
  loading.value = true
  error.value = ''
  quotes.value = []
  try {
    const res = await fetch(`/api/quotes/${quoteId.value}`)
    quote.value = await res.json()
  } catch (e) {
    error.value = 'Failed to load quote'
  }
  loading.value = false
}
</script>

<style scoped>
.quotes-container {
  max-width: 600px;
  margin: 40px auto;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
  padding: 2rem 2.5rem 2.5rem 2.5rem;
  font-family: 'Segoe UI', Arial, sans-serif;
}

h1 {
  text-align: center;
  color: #4f46e5;
  margin-bottom: 1.5rem;
}

.actions {
  display: flex;
  gap: 0.5rem;
  justify-content: center;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
}

.actions input[type="number"] {
  width: 120px;
  padding: 0.4rem;
  border-radius: 6px;
  border: 1px solid #d1d5db;
}

.actions button {
  background: #6366f1;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 0.5rem 1rem;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.2s;
}

.actions button:hover {
  background: #4338ca;
}

.loading {
  text-align: center;
  color: #6366f1;
  font-weight: bold;
}

.error {
  text-align: center;
  color: #dc2626;
  font-weight: bold;
  margin-bottom: 1rem;
}

.quotes-list ul {
  list-style: none;
  padding: 0;
}

.quotes-list li {
  margin-bottom: 1.2rem;
  padding: 1rem;
  background: #f3f4f6;
  border-radius: 8px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
  display: flex;
  flex-direction: column;
}

.quote-text {
  font-size: 1.1rem;
  color: #374151;
  margin-bottom: 0.3rem;
}

.quote-author {
  font-size: 0.95rem;
  color: #6366f1;
  align-self: flex-end;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1rem;
  margin-top: 1rem;
}

.pagination button {
  background: #e0e7ff;
  color: #3730a3;
  border: none;
  border-radius: 6px;
  padding: 0.4rem 1rem;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.2s;
}

.pagination button:disabled {
  background: #c7d2fe;
  color: #a5b4fc;
  cursor: not-allowed;
}

.single-quote {
  margin-top: 2rem;
  background: #f1f5f9;
  border-radius: 10px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.single-quote blockquote {
  margin: 0;
  padding: 0;
  font-size: 1.2rem;
  color: #334155;
  border-left: 4px solid #6366f1;
  padding-left: 1rem;
}

.single-quote footer {
  margin-top: 0.7rem;
  color: #6366f1;
  font-size: 1rem;
  text-align: right;
}
</style>
