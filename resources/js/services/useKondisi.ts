import { useQuery } from "@tanstack/vue-query";
import axios from "axios";

export function useKondisi(options = {}) {
    return useQuery({
        queryKey: ["kondisi"],
        queryFn: async () =>
            await axios.get("/kondisi/get").then((res: any) => res.data.data),
        ...options,
    });    
}