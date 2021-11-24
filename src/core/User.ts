import { writable } from "svelte-local-storage-store";

export const uuid = writable("name", "");
export const token = writable("token", "");
