
export default function AuthenticatedLayout({ header, children }) {
    return (
        <div className="min-h-screen bg-pink-600">
            {header && (
                <header className="flex flex-col items-center">
                    <div className="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {header}
                    </div>
                </header>
            )}

            <main>{children}</main>
        </div>
    );
}
