import { ChakraProvider } from "@chakra-ui/react";
import React from "react";
import { QueryClient, QueryClientProvider } from "react-query";
import { ReactQueryDevtools } from "react-query/devtools";
import { HashRouter } from "react-router-dom";
import Header from "./components/Header";
import Router from "./router/Router";
import "./store";
import theme from "./theme/theme";

const App: React.FC = () => {
	return (
		<HashRouter>
			<ChakraProvider theme={theme}>
				<QueryClientProvider
					client={
						new QueryClient({
							defaultOptions: {
								queries: {
									refetchOnWindowFocus: false,
									refetchOnReconnect: false,
									useErrorBoundary: true,
									retry: false,
								},
							},
						})
					}
				>
					<Header />
					<Router />
					<ReactQueryDevtools initialIsOpen={false} />
				</QueryClientProvider>
			</ChakraProvider>
		</HashRouter>
	);
};

export default App;
