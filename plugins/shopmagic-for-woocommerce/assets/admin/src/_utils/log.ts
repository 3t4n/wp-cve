import { useWpFetch } from "@/composables/useWpFetch";

function log(level: string, message: string, context: object) {
  void useWpFetch("/log").post(
    {
      message,
      level,
      context,
    },
    "json",
  );
}

export function error(message: string, context: object) {
  log("error", message, context);
}
