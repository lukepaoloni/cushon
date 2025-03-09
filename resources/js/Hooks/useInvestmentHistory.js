import { useState } from 'react';
import axios from 'axios';

export function useInvestmentHistory() {
    const [investments, setInvestments] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);

    const fetchInvestments = async () => {
        try {
            setIsLoading(true);
            setError(null);

            const response = await axios.get('/api/retail/isa/investments');
            setInvestments(response.data.data || []);
            return response.data;
        } catch (err) {
            const errorMessage = err.response?.data?.message || 'Failed to load investment history';
            setError(errorMessage);
            console.error('Error fetching investments:', err);
            return null;
        } finally {
            setIsLoading(false);
        }
    };

    return {
        investments,
        isLoading,
        error,
        fetchInvestments
    };
}
