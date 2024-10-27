import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { TransactionList } from './components/TransactionList';
import { TransactionForm } from './components/TransactionForm';

const queryClient = new QueryClient();

function App() {
  return (
    <QueryClientProvider client={queryClient}>
      <div className="min-h-screen bg-gray-100">
        <div className="py-10">
          <header>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
              <h1 className="text-3xl font-bold leading-tight text-gray-900">
                Transaction Management System
              </h1>
            </div>
          </header>
          <main>
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div className="px-4 py-8 sm:px-0">
                <div className="border-4 border-dashed border-gray-200 rounded-lg p-4">
                  <h2 className="text-xl font-semibold mb-4">Create New Transaction</h2>
                  <TransactionForm />
                </div>
                <div className="mt-8">
                  <h2 className="text-xl font-semibold mb-4">Transaction List</h2>
                  <TransactionList />
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </QueryClientProvider>
  );
}

export default App;