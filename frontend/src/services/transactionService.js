import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

const axiosInstance = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

export const transactionService = {
  async getTransactions(params = {}) {
    try {
      const { data } = await axiosInstance.get('/v1/transactions', { params });
      return data;
    } catch (error) {
      console.error('Error fetching transactions:', error);
      throw error;
    }
  },

  async createTransaction(transactionData) {
    try {
      const { data } = await axiosInstance.post('/v1/transactions', transactionData);
      return data;
    } catch (error) {
      console.error('Error creating transaction:', error);
      throw error;
    }
  },

  async deleteTransaction(id) {
    try {
      const { data } = await axiosInstance.delete(`/v1/transactions/${id}`);
      return data;
    } catch (error) {
      console.error('Error deleting transaction:', error);
      throw error;
    }
  }
};