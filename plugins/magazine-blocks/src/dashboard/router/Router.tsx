import React from "react";
import { Route, Routes, useLocation } from "react-router-dom";
import {
	Blocks,
	Dashboard,
	FreeVsPro,
	Help,
	Products,
	Settings,
} from "../screens";

const Router: React.FC = () => {
	const { pathname } = useLocation();

	React.useLayoutEffect(() => {
		const submenu = document.querySelector(
			`.wp-submenu a[href="admin.php?page=magazine-blocks#${pathname}"]`
		);
		if (!submenu) return;
		submenu.parentElement?.classList.add("current");
		return () => {
			submenu.parentElement?.classList?.remove("current");
		};
	}, [pathname]);

	return (
		<Routes>
			<Route path="/" element={<Dashboard />} />
			<Route path="/blocks" element={<Blocks />} />
			<Route path="/Products" element={<Products />} />
			<Route path="/settings" element={<Settings />} />
			<Route path="/free-vs-pro" element={<FreeVsPro />} />
			<Route path="/help" element={<Help />} />
			<Route path="*" element={<Dashboard />} />
		</Routes>
	);
};

export default Router;
