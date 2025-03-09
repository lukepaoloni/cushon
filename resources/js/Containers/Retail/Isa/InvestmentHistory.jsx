import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card.jsx";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/Components/ui/table.jsx";
import { Alert, AlertDescription } from "@/Components/ui/alert";
import { useEffect } from "react";
import { Loader2 } from "lucide-react";
import { useInvestmentHistory } from "@/Hooks";

export default function InvestmentHistory() {
    const { investments, isLoading, error, fetchInvestments } = useInvestmentHistory();

    useEffect(() => {
        fetchInvestments();
    }, []);

    return (
        <Card>
            <CardHeader>
                <CardTitle>Investment History</CardTitle>
            </CardHeader>
            <CardContent>
                {error && (
                    <Alert className="mb-4 bg-red-50 text-red-800">
                        <AlertDescription>
                            {error}
                        </AlertDescription>
                    </Alert>
                )}

                {isLoading ? (
                    <div className="flex justify-center items-center py-10">
                        <Loader2 className="h-8 w-8 animate-spin text-gray-500" />
                    </div>
                ) : investments.length === 0 ? (
                    <div className="text-center py-10 text-gray-500">
                        No investment records found
                    </div>
                ) : (
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Fund</TableHead>
                                <TableHead>Amount</TableHead>
                                <TableHead className="text-right">Date</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {investments.map((investment) => (
                                <TableRow key={investment.id}>
                                    <TableCell>{investment.allocations[0].fund.name}</TableCell>
                                    <TableCell>{investment.allocations[0].formatted_amount}</TableCell>
                                    <TableCell className="text-right">{investment.time_ago}</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                )}
            </CardContent>
        </Card>
    );
}
