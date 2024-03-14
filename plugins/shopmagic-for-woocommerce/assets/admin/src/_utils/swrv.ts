import useSWRV, { type IConfig } from "../../swrv";
import type { fetcherFn, IKey } from "../../swrv/types";
import type { Ref } from "vue";
import { get } from "@/_utils/index";
import LocalStorageCache from "../../swrv/cache/adapters/localStorage";

export default function <Data = unknown, Error = unknown>(
  url: IKey | Ref<string | false | null>,
  fn: fetcherFn<Data> = get,
  config?: IConfig,
) {
  return useSWRV<Data, Error>(
    url,
    fn,
    Object.assign(
      {
        revalidateOnFocus: false,
      },
      config ?? {},
    ),
  );
}

const cache = new LocalStorageCache("shopmagic", 86_400_000);

export { cache };
