import { useState } from 'react';

export default function useInvestment() {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(false);

    const createInvestment = async (fundId, amount) => {
        setIsSubmitting(true);
        setError(null);
        setSuccess(false);

        try {
            const response = await fetch('/api/retail/isa/investments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    fund_id: fundId,
                    amount: amount
                }),
            });

            if (!response.ok) {
                throw new Error('Failed to create investment');
            }

            const data = await response.json();
            setSuccess(true);
            return data;
        } catch (err) {
            setError(err.message);
            console.error('Error creating investment:', err);
            return null;
        } finally {
            setIsSubmitting(false);
        }
    };

    return { createInvestment, isSubmitting, error, success };
}
