import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";
import { Head } from "@inertiajs/react";
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
import { useFunds } from "@/Hooks";
import { Alert, AlertDescription } from "@/Components/ui/alert";

export default function Funds() {
    const [selectedFund, setSelectedFund] = useState('');
    const [selectedAmount, setSelectedAmount] = useState(0);

    const { funds, isLoading: isLoadingFunds, error: fundsError, fetchFunds } = useFunds();

    useEffect(() => {
        fetchFunds();
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
    };

    return (
        <AuthenticatedLayout
            header={
                <h1 className="text-4xl font-extrabold tracking-tight text-white">
                    cushon
                </h1>
            }
        >
            <Head title="Funds" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl px-4 flex flex-col items-center sm:px-6 lg:px-8">
                    <Card className="w-2/3">
                        <CardHeader>
                            <CardTitle>Deposit</CardTitle>
                        </CardHeader>
                        <CardContent>

                            {(fundsError) && (
                                <Alert className="mb-4 bg-red-50 text-red-800">
                                    <AlertDescription>
                                        {fundsError}
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
                                        onValueChange={val => setSelectedAmount(val)}
                                    />
                                </div>
                                <Button
                                    type="submit"
                                    disabled={!selectedFund || selectedAmount <= 0}
                                >
                                    Deposit
                                </Button>
                            </form>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AuthenticatedLayout>
    )
}
