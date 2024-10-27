import axios from 'axios';

const API_URL = 'http://localhost:8000/api/v1';

export const transactionService = {
  async getTransactions(params = {}) {
    const { data } = await axios.get(`${API_URL}/transactions`, { params });
    return data;
  },

  async createTransaction(transactionData) {
    const { data } = await axios.post(`${API_URL}/transactions`, transactionData);
    return data;
  },

  async deleteTransaction(id) {
    const { data } = await axios.delete(`${API_URL}/transactions/${id}`);
    return data;
  }
};