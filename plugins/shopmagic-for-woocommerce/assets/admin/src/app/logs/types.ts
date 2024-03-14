import type { Automation } from "@/types/automation";
import type { Client } from "@/stores/clients";

export type Queue = {
  id: number;
  execution_id: string;
  automation: {
    id: number;
    name: string;
    actions: Record<number, string>;
  } | null;
  customer: {
    id: string;
    email: string;
    guest: boolean;
  } | null;
  schedule: string | null;
};

type OutcomeError = {
  note: string;
  context: {
    ErrorCode: number;
    Trace: string;
  };
};

export type Outcome = {
  id: number;
  status: "completed" | "failed" | "unknown" | "pending";
  automation: Automation;
  customer: Client;
  action: string;
  updated: string;
  timestamp?: string;
  error?: OutcomeError | null;
};

export type ReadOutcome = Readonly<Outcome>;
