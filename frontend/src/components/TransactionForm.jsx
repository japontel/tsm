import React from 'react';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { transactionService } from '../services/transactionService';

export const TransactionForm = () => {
  const queryClient = useQueryClient();
  const [formData, setFormData] = React.useState({
    account_number_from: '',
    account_number_type_from: '',
    account_number_to: '',
    account_number_type_to: '',
    amount: '',
    type: 'credit',
    description: '',
    reference: '',
    creation_date: new Date().toISOString().split('T')[0]
  });

  const mutation = useMutation({
    mutationFn: (newTransaction) => transactionService.createTransaction(newTransaction),
    onSuccess: () => {
      queryClient.invalidateQueries(['transactions']);
      // Reset form
      setFormData({
        account_number_from: '',
        account_number_type_from: '',
        account_number_to: '',
        account_number_type_to: '',
        amount: '',
        type: 'credit',
        description: '',
        reference: '',
        creation_date: new Date().toISOString().split('T')[0]
      });
    }
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    mutation.mutate(formData);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6 bg-white p-6 rounded-lg shadow">
      <div className="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
        <div>
          <label htmlFor="account_number_from" className="block text-sm font-medium text-gray-700">
            From Account
          </label>
          <input
            type="text"
            name="account_number_from"
            value={formData.account_number_from}
            onChange={handleChange}
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500"
          />
        </div>

        <div>
          <label htmlFor="account_number_type_from" className="block text-sm font-medium text-gray-700">
            From Account Type
          </label>
          <input
            type="text"
            name="account_number_type_from"
            value={formData.account_number_type_from}
            onChange={handleChange}
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500"
          />
        </div>

        <div>
          <label htmlFor="account_number_to" className="block text-sm font-medium text-gray-700">
            To Account
          </label>
          <input
            type="text"
            name="account_number_to"
            value={formData.account_number_to}
            onChange={handleChange}
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500"
          />
        </div>

        <div>
          <label htmlFor="account_number_type_to" className="block text-sm font-medium text-gray-700">
            To Account Type
          </label>
          <input
            type="text"
            name="account_number_type_to"
            value={formData.account_number_type_to}
            onChange={handleChange}
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500"
          />
        </div>

        <div>
          <label htmlFor="amount" className="block text-sm font-medium text-gray-700">
            Amount
          </label>
          <input
            type="number"
            name="amount"
            value={formData.amount}
            onChange={handleChange}
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500"
          />
        </div>

        <div>
          <label htmlFor="type" className="block text-sm font-medium text-gray-700">
            Type
          </label>
          <select
            name="type"
            value={formData.type}
            onChange={handleChange}
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500"
          >
            <option value="credit">Credit</option>
            <option value="debit">Debit</option>
          </select>
        </div>
      </div>

      <div>
        <label htmlFor="description" className="block text-sm font-medium text-gray-700">
          Description
        </label>
        <textarea
          name="description"
          value={formData.description}
          onChange={handleChange}
          rows={3}
          className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500"
        />
      </div>

      <div>
        <button
          type="submit"
          disabled={mutation.isLoading}
          className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          {mutation.isLoading ? 'Creating...' : 'Create Transaction'}
        </button>
      </div>

      {mutation.isError && (
        <div className="text-red-500 text-sm">
          Error: {mutation.error.message}
        </div>
      )}
    </form>
  );
};