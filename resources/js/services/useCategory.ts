import { useQuery } from "@tanstack/vue-query";
import axios from "axios";

export function useCategory(options = {}) {
    return useQuery({
        queryKey: ["category"],
        queryFn: async () =>
            await axios.get("/category/get").then((res: any) => res.data.data),
        ...options,
    });    
}