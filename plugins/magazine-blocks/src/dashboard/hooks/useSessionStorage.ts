import { Prettify } from "blocks/components/types";
import { createStorage, UseStorageOptions } from "./create-storage";

const useSessionStorage = <T = string>(props: Prettify<UseStorageOptions<T>>) =>
	createStorage<T>("sessionStorage", "use-session-storage")(props);

export default useSessionStorage;
