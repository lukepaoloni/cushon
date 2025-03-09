import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";
import { Head } from "@inertiajs/react";
import { useState } from "react";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/Components/ui/tabs.jsx";
import FundsDeposit from "@/Containers/Retail/Isa/FundsDeposit";
import InvestmentHistory from "@/Containers/Retail/Isa/InvestmentHistory";

export default function RetailIsaDashboard() {
    const [activeTab, setActiveTab] = useState("deposit");

    return (
        <AuthenticatedLayout
            header={
                <h1 className="text-4xl font-extrabold tracking-tight text-white">
                    cushon
                </h1>
            }
        >
            <Head title="Retail ISA" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <Tabs defaultValue="deposit" value={activeTab} onValueChange={setActiveTab} className="w-full">
                        <TabsList className="grid w-full grid-cols-2 mb-8">
                            <TabsTrigger value="deposit">Deposit</TabsTrigger>
                            <TabsTrigger value="history">Investment History</TabsTrigger>
                        </TabsList>
                        <TabsContent value="deposit">
                            <FundsDeposit refreshHistory={() => setActiveTab("history")} />
                        </TabsContent>
                        <TabsContent value="history">
                            <InvestmentHistory />
                        </TabsContent>
                    </Tabs>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
