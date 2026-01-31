{-# LANGUAGE DataKinds #-}
{-# LANGUAGE NoImplicitPrelude #-}
{-# LANGUAGE TemplateHaskell #-}
{-# LANGUAGE ScopedTypeVariables #-}

module MintingPolicy where

import PlutusTx
import PlutusTx.Prelude
import Plutus.V2.Ledger.Api
import Plutus.V2.Ledger.Contexts

{-# INLINABLE mkPolicy #-}
mkPolicy :: PubKeyHash -> BuiltinData -> ScriptContext -> Bool
mkPolicy pkh _ ctx =
    txSignedBy (scriptContextTxInfo ctx) pkh

policy :: PubKeyHash -> MintingPolicy
policy pkh = mkMintingPolicyScript $
    $$(PlutusTx.compile [|| \pkh' -> mkPolicy pkh' ||])
    `PlutusTx.applyCode`
    PlutusTx.liftCode pkh

