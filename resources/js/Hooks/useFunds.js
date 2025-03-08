import { useState } from 'react';

export default function useFunds() {
    const [funds, setFunds] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState(null);

    const fetchFunds = async () => {
        setIsLoading(true);
        setError(null);

        try {
            const response = await fetch('/api/retail/isa/funds');

            if (!response.ok) {
                throw new Error('Failed to fetch funds');
            }

            const data = await response.json();
            setFunds(data.data);
            return data.data;
        } catch (err) {
            setError(err.message);
            console.error('Error fetching funds:', err);
        } finally {
            setIsLoading(false);
        }
    };

    return { funds, isLoading, error, fetchFunds };
}
