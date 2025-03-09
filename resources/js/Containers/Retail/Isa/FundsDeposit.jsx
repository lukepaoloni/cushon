import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card.jsx";
import { Label } from "@/Components/ui/label.jsx";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue
} from "@/Components/ui/select.jsx";
import { MoneyInput } from "@/Components/ui/MoneyInput.jsx";
import { Button } from "@/Components/ui/button.jsx";
import { useState, useEffect } from "react";
import { useFunds, useInvestment } from "@/Hooks";
import { Alert, AlertDescription } from "@/Components/ui/alert";
import { Loader2 } from "lucide-react";

export default function FundsDeposit({ refreshHistory }) {
    const [selectedFund, setSelectedFund] = useState('');
    const [selectedAmount, setSelectedAmount] = useState(0);

    const { funds, isLoading: isLoadingFunds, error: fundsError, fetchFunds } = useFunds();
    const { createInvestment, isSubmitting, error: investmentError, success } = useInvestment();

    useEffect(() => {
        fetchFunds();
    }, []);

    useEffect(() => {
        if (success && refreshHistory) {
            const timer = setTimeout(() => {
                refreshHistory();
            }, 1500);
            return () => clearTimeout(timer);
        }
    }, [success, refreshHistory]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (selectedFund && selectedAmount > 0) {
            const data = await createInvestment(selectedFund, selectedAmount);

            if (data) {
                setSelectedFund('');
                setSelectedAmount(0);
            }
        }
    };

    return (
        <Card>
            <CardHeader>
                <CardTitle>Deposit</CardTitle>
            </CardHeader>
            <CardContent>
                {success && (
                    <Alert className="mb-4 bg-green-50 text-green-800">
                        <AlertDescription>
                            Investment created successfully!
                        </AlertDescription>
                    </Alert>
                )}

                {(fundsError || investmentError) && (
                    <Alert className="mb-4 bg-red-50 text-red-800">
                        <AlertDescription>
                            {fundsError || investmentError}
                        </AlertDescription>
                    </Alert>
                )}

                <form onSubmit={handleSubmit} className="grid w-full items-center gap-4">
                    <div className="flex flex-col space-y-1.5">
                        <Label htmlFor="funds">Funds</Label>
                        <Select
                            id="funds"
                            name="funds"
                            value={selectedFund}
                            onValueChange={val => setSelectedFund(val)}
                            disabled={isLoadingFunds}
                        >
                            <SelectTrigger>
                                <SelectValue placeholder="Select a fund" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Funds</SelectLabel>
                                    {isLoadingFunds ? (
                                        <SelectItem value="loading" disabled>Loading funds...</SelectItem>
                                    ) : (
                                        funds.map(fund => (
                                            <SelectItem key={fund.id} value={fund.id.toString()}>
                                                {fund.name}
                                            </SelectItem>
                                        ))
                                    )}
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                    <div className="flex flex-col space-y-1.5">
                        <Label htmlFor="amount">Amount</Label>
                        <MoneyInput
                            id="amount"
                            prefix="£"
                            allowNegativeValue={false}
                            placeholder="Specify amount to deposit into selected fund."
                            decimalsLimit={2}
                            value={selectedAmount}
                            onValueChange={(value, name, values) => setSelectedAmount(values.float)}
                        />
                    </div>
                    <Button
                        type="submit"
                        disabled={!selectedFund || selectedAmount <= 0 || isSubmitting}
                    >
                        {isSubmitting && <Loader2 className="mr-2 h-4 w-4 animate-spin" />}
                        {isSubmitting ? 'Processing...' : 'Deposit'}
                    </Button>
                </form>
            </CardContent>
        </Card>
    );
}
